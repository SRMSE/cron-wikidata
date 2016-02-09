from time import strftime
import json
bcolors={
	"HEADER" : '\033[95m',
    "INFO" : '\033[94m',
    "SUCCESS" : '\033[92m',
    "WARNING" : '\033[93m',
    "FAIL" : '\033[91m',
    "ENDC" : '\033[0m',
    "BOLD" : '\033[1m',
    "UNDERLINE" : '\033[4m'
}
config=json.loads(open("nconfig.json","r").read())
def putmsg(mssg,code):
	f=open(config["logfile"],"a")
	print bcolors[code.upper()]+""+"["+strftime("%Y-%m-%d %H:%M:%S")+"]\t["+code.strip().capitalize()+"]\t"+mssg+"" + bcolors["ENDC"]
	f.write("["+strftime("%Y-%m-%d %H:%M:%S")+"]\t["+code.strip().capitalize()+"]\t"+mssg+"\n")
	f.close()
def headmsg(mssg,code):
	f=open(config["head_log"],"a")
	print bcolors[code.upper()] + ""+"["+strftime("%Y-%m-%d %H:%M:%S")+"]\t["+code.strip().capitalize()+"]\t"+mssg+"" + bcolors["ENDC"]
	f.write("["+strftime("%Y-%m-%d %H:%M:%S")+"]\t["+code.strip().capitalize()+"]\t"+mssg+"\n")
	f.close()