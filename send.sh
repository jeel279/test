#!/bin/bash

curl -v --ssl-reqd \
  --url 'smtp://us2.smtp.mailhostbox.com:587' \
  --user 'founder@attendworks.tech:BwEFSJu9' \
  --header 'From:founder@attendworks.tech' \
  --mail-from 'founder@attendworks.tech' \
  --mail-rcpt 'jeel4402@gmail.com' \
  --upload-file mail.txt
  -F mail.txt
