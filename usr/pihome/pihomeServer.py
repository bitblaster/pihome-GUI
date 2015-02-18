#!/usr/bin/python
from apscheduler.triggers.cron import CronTrigger
import sys
from gi.overrides.keysyms import section

###TODO: spostare le chiavi in un file di configurazione separato e cambiarle

# PiHome v1.0
# http://pihome.harkemedia.de/
# 
# PiHome Copyright  2012, Sebastian Harke
# Lizenz Informationen.
# 
# This work is licensed under the Creative Commons Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 3.0 Unported License. To view a copy of this license,
# visit: http://creativecommons.org/licenses/by-nc-sa/3.0/.

# Config section
config={}
config['DB_HOST']             = "localhost";
config['DB_USER']             = "root";
config['DB_PWD']              = "root";
config['DB_NAME']             = "pihome";

config['encrypt_iv']          = "12345678";
config['encrypt_passphrase']  = "1234567890abcdef";
config['server_port']         = "8444";

if len(sys.argv) > 1 and sys.argv[1] == "1":
    DEBUG=1
    LOG_PATH="pihome.log"
else:
    DEBUG=0
    LOG_PATH="/var/log/pihome.log"
    
import time
if not DEBUG:
    import RPi.GPIO as GPIO
import cgi,time,string,datetime
import urlparse
from os import curdir, sep, path
from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer
from Crypto.Cipher import Blowfish
import base64
import MySQLdb as mdb
from fuzzywuzzy import fuzz
from fuzzywuzzy import process
from apscheduler.schedulers.background import BackgroundScheduler
from datetime import datetime, timedelta
import json
import logging
import threading

logFormatter = logging.Formatter("%(asctime)s [%(threadName)-12.12s] [%(levelname)-5.5s]  %(message)s")

fileHandler = logging.FileHandler(LOG_PATH)
fileHandler.setFormatter(logFormatter)
logging.getLogger().addHandler(fileHandler)

consoleHandler = logging.StreamHandler()
consoleHandler.setFormatter(logFormatter)
logging.getLogger().addHandler(consoleHandler)

logging.getLogger().setLevel(logging.DEBUG)
#logging.basicConfig(filename='/var/log/pihome.log',level=logging.DEBUG)

# Set to use IO No.
if not DEBUG:
    GPIO.setmode(GPIO.BOARD)

# Mappatura pulsanti telecomando / GPIO del Raspberry
ioPorts={"2":11, "4":12, "6":13, "8":15, "10":16, "12":18, "A":19, "B": 21, "C":23}

devicesByName={}
devicesById={}
groups={}
knownClients={}

threadLock = threading.Lock()
scheduler = BackgroundScheduler()

logging.info("Setup GPIO ports: " + ", ".join(str(x) for x in ioPorts.values()))
             
for k, port in ioPorts.iteritems():
    if not DEBUG:
        GPIO.setup(port, GPIO.OUT)
        GPIO.output(port, False)

class Handler(BaseHTTPRequestHandler):
    def do_GET(self):
        try:
            start = time.time()
            
            #print "Path: " + self.path
            encryptedString = str(self.path)[1:]
            #print "Enc string: " + encryptedString
            cipher = Blowfish.new(config['encrypt_passphrase'], Blowfish.MODE_CBC, config['encrypt_iv'])
            # usiamo rstrip("\x00") per rimuovere caratteri terminatori di padding usati dal Blowfish
            datastring = cipher.decrypt(base64.urlsafe_b64decode(encryptedString)).rstrip("\x00")
            logging.debug("Decrypted datastring: " + datastring)

            outStr=""
            
            parsed = urlparse.urlparse(datastring)
            additionalArgs=urlparse.parse_qs(parsed.query)
            if "client" in additionalArgs and "time" in additionalArgs:
                client =  additionalArgs["client"][0]
                timestamp = int(additionalArgs["time"][0])
                if client in knownClients:
                    lastTimestamp = knownClients[client]
                    if lastTimestamp >= timestamp:
                        raise Exception, "Invalid request"
                    
                knownClients[client] = timestamp
             
            split = parsed.path.split("/")
            
            if split[0] == "switchDevice":
                if len(split) < 3:
                    raise Exception, "Not enough parameters for command request. Datastring: " + datastring
                
                deviceId = string.upper(split[1])            
                logging.info("Executing switchDevice for device: " + deviceId)
                                        
                action = split[2]
                if action not in ["on", "off", "toggle"]:
                    raise Exception, "Invalid action for command request: " + action
                    
                executeManualCommand(deviceId, action)
            elif split[0] == "switchDeviceFuzzy":
                if len(split) < 2:
                    raise Exception, "Not enough parameters for command switchDeviceFuzzy. Datastring: " + datastring

                logging.info("Executing fuzzy matching '" + split[1] + "' over " + str(devicesByName.keys()))
                deviceFound = process.extractOne(split[1], devicesByName.keys(), score_cutoff=50)
                if deviceFound is not None:
                    device = devicesByName[deviceFound[0]]
                    logging.debug("Matched device '" + device["name"] + "'")
                    
                    if "accend" in split[1] or "attiv" in split[1] or "invert" in split[1]:
                        action = "on"
                    elif "spegn" in split[1] or "disattiv" in split[1]:
                        action = "off"
                    else:
                        raise Exception, "Invalid action for command switchDeviceFuzzy: " + split[1]
                    
                    executeManualCommand(device["id"], action)
                else:
                    raise Exception, "No device found matching '" + split[1] + "'"
            elif split[0] == "allOff" or split[0] == "allOn":
                if len(split) < 2:
                    raise Exception, "Not enough parameters for command switchDeviceFuzzy. Datastring: " + datastring
               
                if split[1].startswith("group:"):
                    groupName = split[1].split(":")[1]
                    if groupName == "all" or groupName in groups:
                        logging.info("Executing allOff on group: " + groupName)
                        executeManualCommand(split[1], "off" if split[0] == "allOff" else "on")
                    else:
                        raise Exception, "Invalid group for command allOff: " + groupName
                else:
                    raise Exception, "Invalid parameter for command allOff: " + split[1]
            elif split[0] == "reloadDevices":
                logging.info("Executing reloadDevices")
                loadDevices()
            elif split[0] == "readJobs":
                if len(split) < 2:
                    deviceId = None
                    logging.info("Executing full readJobs")
                else:
                    deviceId = split[1]
                    logging.info("Executing readJobs for device: " + deviceId)
                
                jobs = scheduler.get_jobs()
                for job in jobs:
                    if deviceId and job.args[0] != deviceId:
                        continue
                    
                    cronFields = {a.name : a.__str__() for a in job.trigger.fields}
                    jobMap = {"jobID": job.id, "deviceId": job.args[0], "action": job.args[1], "cronFields": cronFields}
                    #print json.dumps(jobMap)
                    outStr += json.dumps(jobMap) + "|"
                outStr = outStr[:-1]
                
                logging.debug("Jobs read for deviceId '" + str(deviceId) + "':" + outStr)
            elif split[0] == "addJob":
                deviceId = split[1]
                logging.info("Executing addJob for device: " + deviceId)
                
                #job = scheduler.add_job(scheduledAction, 'cron', coalesce=True, id=None, args=[deviceId, "disabled"], year="*", month="*", day="*", day_of_week="*", hour="*", minute="*", second="0")
                triggerArgs = {"year":"*", "month":"*", "day":"*", "day_of_week":"*", "hour":"*", "minute":"*", "second":"0"}
                job = scheduler.add_job(executeScheduledCommand, trigger='cron', coalesce=True, id=None, args=[deviceId, "disabled"], **triggerArgs)
                scheduler.pause_job(job.id)
            elif split[0] == "saveJob":
                jsonString = split[1]
                logging.info("Executing saveJob: jsonString=" + jsonString)

                jsonObj = json.loads(jsonString)
                
                jobId = jsonObj["jobID"]
                deviceId = jsonObj["deviceId"]
                action = jsonObj["action"]
                cronFields = jsonObj["cronFields"]
                f = {key : ','.join(value) for key, value in cronFields.iteritems()}
                
                triggerArgs = {"year":f["year"], "month":f["month"], "day":f["day"], "day_of_week":f["day_of_week"], "hour":f["hour"], "minute":f["minute"], "second":f["second"]}
                job = scheduler.add_job(executeScheduledCommand, trigger='cron', coalesce=True, id=jobId, args=[deviceId, action], replace_existing=True, **triggerArgs)

                #trigger = CronTrigger(year=f["year"], month=f["month"], day=f["day"], day_of_week=f["day_of_week"], hour=f["hour"], minute=f["minute"], second=f["second"])
                #jobFields = {"trigger": trigger, "args": [deviceId, action]}
                #job = scheduler.modify_job(jobId, **jobFields)
                if action == "disabled":
                    scheduler.pause_job(job.id)
                #else:
                 #   scheduler.pause_job(job.id)
            elif split[0] == "removeJob":
                jobId = split[1]
                logging.info("Executing removeJob for job: " + jobId)
                
                scheduler.remove_job(jobId)
            else:
                raise Exception, "Received invalid datastring: " + datastring
                
            end = time.time()
            logging.debug("Command processed in " + str(end - start) + " sec.")
            
            self.send_response(200, outStr)
            self.send_header('Content-type', 'text/html')
            self.end_headers()
            self.wfile.write(outStr)
            return
        except IOError, e:
            logging.exception(e)
            self.send_error(404,'File Not Found: ' + self.path)
        except Exception, msg:
            logging.exception(msg)
            self.send_error(500, str(msg))

def executeScheduledCommand(deviceId, action):
    executeCommand(deviceId, action, True)
    
def executeManualCommand(deviceId, action):
    t = threading.Thread(target=executeCommand, args=(deviceId, action, False))
    t.start()
    #executeCommand(deviceId, action, False)

def executeCommand(deviceId, action, scheduled):
    # Commands spanning all devices in a group are executed passing deviceId="group:xxx"
    if deviceId.startswith("group:"):
        groupName = deviceId.split(":")[1]
        if groupName == "all":
            for id in devicesById.keys():
                executeCommand(id, action, scheduled)
        else:
            if groupName in groups:
               groupDevices = groups[groupName]
               for device in groupDevices:
                   executeCommand(device["id"], action, scheduled)
        return
        
    device = devicesById[deviceId]
    flags = device["flags"]
    code = device["code"]
    
    # Get lock to synchronize threads
    logging.debug("Acquiring lock...")
    threadLock.acquire()

    try:
        logging.debug("Lock acquired!")
        
        logStr = "Flag ports: "
        for flag in flags:
            logStr += str(ioPorts[flag]) + ","
            if not DEBUG:
                GPIO.output(ioPorts[flag], True)
        logStr = logStr [:-1] + ". "
        logStr += "Command port:" + str(ioPorts[code])
        logging.debug(logStr)
        
        # Ci assicuriamo che il transistor abbia switchato nel telecomando
        time.sleep(0.05)
        
        if not DEBUG:
            GPIO.output(ioPorts[code], True)
        if action == "off":
            delay=0.2
        else:
            delay=1
            
        logging.debug("Sleeping for: " + str(delay) + " seconds")
        time.sleep(delay)
        
        if not DEBUG:
            GPIO.output(ioPorts[code], False)
        
        for flag in flags:
            if not DEBUG:
                GPIO.output(ioPorts[flag], False)
    except Exception, msg:
        logging.exception(msg)
        if not scheduled:
            raise
        
    # Free lock to release next thread
    threadLock.release()
    logging.debug("Lock released!")

    if device is not None and device["type"] == "delaySwitch" and device["status"] != "-1":
        try:
            logging.debug("Cambio stato al dispositivo" + str(device))
            con = mdb.connect('localhost', 'root', 'root', 'pihome')
            cur = con.cursor()
            if action == "off":
                status="0"
            else:
                status="1"
        
            cur.execute ("UPDATE pi_devices SET status=%s WHERE id=%s", (status, deviceId))
            con.commit()
            cur.close()
            con.close()
        except Exception, msg:
            logging.exception(msg)
            self.send_error(500, str(msg))
        
def loadDevices():
    devicesByName.clear()
    devicesById.clear()
    groups.clear()
    
    con = mdb.connect(config['DB_HOST'], config['DB_USER'], config['DB_PWD'], config['DB_NAME'])
    cur = con.cursor()
    cur.execute("select d.id, d.device, d.flags, d.code, d.type, d.status, g.id, g.group_name from pi_devices d join pi_groups g on g.id=d.group_id")
    rows = cur.fetchall()
    for row in rows:
        device = {"id":str(row[0]), "name":row[1], "flags":row[2], "code":row[3], "type":row[4], "status":row[5], "group_id":str(row[6]), "group_name":row[7]}
        devicesByName[row[1]] = device
        devicesById[str(row[0])] = device
        if not str(row[6]) in groups:
            groups[str(row[6])]=[]
        
        groups[str(row[6])].append(device)
        
    cur.close()
    con.close()
    
    logging.debug("Read " + str(len(devicesByName)) + " devices: " + str(devicesByName))
    return devicesByName
    
def main():
    try:
        logging.info('-------- PiHome startup --------')
        loadDevices()
        
        scheduler.add_jobstore('sqlalchemy', url='mysql://' + config['DB_USER'] + ':' + config['DB_PWD'] + '@' + config['DB_HOST'] + '/' + config['DB_NAME'])
        scheduler.start()
    
        srv = HTTPServer(('', 8444), Handler)
        logging.info('PiHome Server STARTED')
        srv.serve_forever()
    except KeyboardInterrupt:
        logging.info('PiHome Server STOPPED')
        if not DEBUG:
            GPIO.cleanup()
        srv.socket.close()

if __name__ == '__main__':
    main()

