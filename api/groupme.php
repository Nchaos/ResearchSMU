<?php
	/*==========================================================================================================================================================\\
	||		Create New Group																																	||
	||==========================================================================================================================================================||
	||																																							||
	||	curl -X POST -H "Content-Type: application/json" -d '{"name": "<Group Name>", "share": "false"}' https://api.groupme.com/v3/groups?token=<Access Token>	||
	||																																							||
	||	+name																																					||
	||	-description																																			||
	||	*share: false																																			||
	\\==========================================================================================================================================================*/
	
	
	/*======================================================================================================================================================================================================\\
	||		Create New Bot For Group																																										||
	||======================================================================================================================================================================================================||
	||																																																		||
	||	curl -X POST -H "Content-Type: application/json" -d '{"bot": {"name": "<User's Name>", "group_id": "<Group ID>", "callback_url": "<URL>"}}' https://api.groupme.com/v3/bots?token=<Access Token>	||	
	||																																																		||
	||	+bot[name]																																															||
	||	+bot[group_id]	13629414																																											||
	||	-bot[callback_url]																																													||
	\\======================================================================================================================================================================================================*/
	
	
	/*==========================================================================================================================================\\
	||		Post A Message																														||
	||==========================================================================================================================================||
	||																																			||
	||	curl -X POST -H "Content-Type: application/json" -d '{"bot_id": "<Bot Id>", "text": "<Message>"}' https://api.groupme.com/v3/bots/post	||
	||																																			||
	||	+bot_id		181f76df6d707dfcf8fcfaef90		6fda211a563a38374c3f4c2327																	||
	||	+text																																	||
	\\==========================================================================================================================================*/





?>