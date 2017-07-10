# streamlabs_coding_assignment

Author: Runxi Ding

github: https://github.com/sunnyding602/streamlabs_coding_assignment

First of all, this coding assignment is online. visist http://runxiflute.com 
And here is a video to demonstrate this assignment.  https://youtu.be/f36nyjBOUBE

#How to deploy my project on your own machine
N/A, since deploy skill will not be judged...

#The arhitecture:
I use PHP for developing the frontend related work, Node.JS for chat-service and MariaDB for storage. Here's the reason:
We should really use a language to its strength. Admittedly, PHP can do websocket, but it's better to leave the work to Node.JS. 


#Problems encountered
1. We are using username from youtube, however youtube's username does not guarantee username's uniqueness
   solution yet not implemented: ask for a new username when facing a duplicated username


#API Doc
GET http://runxiflute.com/api/get_chat_msg.php?username=[username]
Params: username(String)
Return: An array of messages in json format.
Sample Return:
```
[
    {"id":"1","username":"xbz m","msg":"hey","room":"general","ctime":"2017-07-10 01:44:57"},
    {"id":"2","username":"xbz m","msg":"yo","room":"general","ctime":"2017-07-10 01:45:12"},
]
```


#Algorithm of the Hype
An array that adds all messages into it, and shifts messages out if this message is outdated for 1 second.
