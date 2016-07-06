define({
	"host" : "http://localhost:8000",
	"hostLocal" : "http://localhost",
	"apiName" : "api",
	"numMaxBlocks": 16,
	"FridayDay": "Friday",
	"SaturdayDay": "Saturday",
	"SundayDay": "Sunday",
	"yes": "Yes",
	"no": "No",
	"add": "Add",
	"roles":{
		"Player":"Player",
		"TournamentAdmin":"TournamentAdmin",
		"Admin":"Admin"
	},
	"sections":[
        {'key':'basicInfo', 'text':'Basic Information'},
        {'key':'annotations', 'text':'Annotations'},
        {'key':'tournaments', 'text':'Tournaments'},
        {'key':'inscriptions', 'text':'Inscriptions'},
        {'key':'games', 'text':'Games'},
        {'key':'pairs', 'text':'My pairs Info'}
    ],
    "scoreSelectOptions":[
        {'id':'-1', 'text':'- Set -'},
        {'id':'0', 'text':'0'},
        {'id':'1', 'text':'1'},
        {'id':'2', 'text':'2'},
        {'id':'3', 'text':'3'},
        {'id':'4', 'text':'4'},
        {'id':'5', 'text':'5'},
        {'id':'6', 'text':'6'},
        {'id':'7', 'text':'7'}
    ],
    "tournamentStatus":{
    	"Created":"Created",
    	"InInscriptionDate":"In Inscription Date",
    	"InGroupDate":"In Group Date",
    	"MatchsDone": "Group phase (Matchs done)",
    	"InFinalsDate": "In Finals Date"
    },
    "gameStatus":{
    	"Created":"Created",
    	"WonPair1":"Won Pair 1",
    	"WonPair2":"Won Pair 2"
    },
    "adminTournamentsView":"My Admin Tournaments",
	"fields":{
		"email":"Email",
		"name":"Name",
		"lastName":"LastName",
		"registrationDate":"Registration Date",
		"status":"Status",
		"address":"Address",
		"city":"City",
		"country":"Country",
		"cp":"Postal Code",
		"firstPhone":"First Phone",
		"gameLevel":"Game Level",
		"secondPhone":"Second Phone",
		"gender":"Gender"
	},
	"finalOptions":[
		{'id':'league', 'text':'league according (No Draw)'},
		{'id':'draw2', 'text':'Draw 2 Pairs (final)'},
		{'id':'draw4', 'text':'Draw 4 Pairs (semifinals)'},
		{'id':'draw8', 'text':'Draw 8 Pairs (Quarter finals)'},
		{'id':'draw16', 'text':'Draw 16 Pairs (Second round)'},
		{'id':'draw32', 'text':'Draw 32 Pairs (Knockout phase)'},
		{'id':'firstSecond', 'text':'First and Second for each group'},
		{'id':'first', 'text':'Only first for each group'}
	],
	"startTournament":"Start Tournament",
	"buttonBasicInfo":"Edit Profile",
	"cancelButtonTitle":"Cancel",
	"saveButtonTitle":"Save",
	"saveGroupsButtonTitle":"Save Groups",
	"saveAndDoMatchsButtonTitle":"Save Groups and Do Matchs",
	"saveDoMatchsButtonTitle":"Save Do Matchs",
	"nextStepButtonTitle": "Next",
	"backStepButtonTitle": "Back",
	"editGroupViewButton" : "Edit Groups",
	"dragAndDropForAddPairs": "Drag and Drop for add pairs",
	"deleteGroupButton": "Delete Group",
	"movementNotAllowed": "Movement not Allowed",
	"loadingMessage":"Loading",
	"modalTitleProfile":"Edit Profile",
	"modalTitleInscription":"Do Inscription To",
	"modalTitleObservation": "Edit Observations",
	"noHaveObservationsAndNotCanInsert": "You not have observations and can`t insert because the matches are made",
	"modalTitleAnnotation": "Insert Annotation",
	"modalTitleNewTournamentType" : "New Tournament (Type of Tournament)",
	"modalTitleNewTournamentBasicInfo": "New Tournament (Basic Info)",
	"modalTitleNewTournamentCategory" : "New Tournament (Insert Categories)",
	"modalTitleInsertTournamentSchedule" : "Insert Tournament Shedule",
	"modalTitleEditGroups" : "Edit grups",
	"modalTitleScore" : "Edit Score",
	"modalTitleCloseGroups" : "Select pairs classified for each category",
	"modalTitleDraw" : "Draws",
	"scoreIncorrect" : "Score is in incorrect format",
	"createNewTournament":"Create Tournament",
	"confirmSaveProfile":"Are you sure save changes?",
	"DoGroupConfirm":"If close inscriptions any more can sing up in the tournament, Are you sure?",
	"DoMatchsConfirm":"If do matchs you can´t do any changes in the groups, Are you sure?",
	"confirmDeleteAnnotation": "Are you sure delete annotation",
	"sureCloseGroups": "Are you sure to close groups?",
	"tournamentsFields":{
		"name":"Name",
		"admin":"Admin",
		"startInscriptionDate":"Start Inscription Date",
		"startGroupDate":"Start Tournament",
		"status":"Status",
		"options":"Options"
	},
	"inscriptionsFields":{
		"pair":"Pair",
		"category":"Category",
		"tournament":"Tournament",
		"group":"Group",
		"status":"Status",
		"options":"Options"
	},
	"pairsFields":{
		"pair":"Pair"
	},
	"annotationsFields":{
		"creationDate":"Creation Date",
		"text":"Text",
		"status":"Status",
		"options":"Options"
	},
	"gamesFields":{
		"gameDate":"Match Date",
		"tournament":"Tournament",
		"status":"Status",
		"score":"Score",
		"options":"Options",
		"DateNotAvailable": "Date not available",
		"GameNotPlayed":"Game not played"
	},
	"categoryFields":{
		"name":"Name",
		"gender":"Gender",
		"registeredLimit":"MAX Inscriptions",
		"selectGenderPlaceholder":"Gender",
		"registeredMin":"MIN Inscriptions"
	},
	"rangeFields":{
		"selectTrack":"Insert number of tracks",
		"insertRange":"Insert new range",
		"range":"Range",
		"selectRangePlaceholder":"Insert hours ranges (Example '9 - 10:30')",
		"selectDaysPlaceholder":"Select days for apply range",
		"invalidRangeFormat": "Invalid format, Enter(16 - 17:30)"
	},
	"newTournamentFields":{
		"name":"Name",
		"startInscriptionDate":"Start Inscription Date",
		"endInscriptionDate":"End Inscription Date",
		"startGroupDate":"Start Group Date",
		"endGroupDate":"End Group Date",
		"startFinalDate":"Start Finals Date",
		"endFinalDate":"End Finals Date",
		"registeredLimit":"Registered Limit",
		"image":"Tournament image"

	},
	"viewInscriptionsAdmin":{
		"observations":"Observations",
		"player1": "Player 1",
		"player2": "Player 2",
		"category":"Category",
		"options":"Options",
		"placeholderPair":"Insert your pair name",
	},
	"viewGamesAdmin":{
		"pair1":"Pair 1",
		"pair2":"Pair 2"
	},
	"fullCalendar":{
		"track":"Track",
		"addTrack":"Add Track",
		"confirmDeleteTrack":"Are you sure you want to delete ",
		"addTrackAlert":"Add Track name"
	},
	"InscriptionsMode":"Inscriptions",
	"GamesMode":"Games",
	"doInscription":"Sign Up",
	"viewObservation":"View Observations",
	"modifyAnnotation": "Modify Annotation",
	"deleteAnnotation": "Delete Annotation",
	"createAnnotation": "New Annotation",
	"insertAnnotation": "Insert text",
	"deleteInscription": "Delete Observation",
	"AreSureDeleteInscription": "Are you sure to delete Inscription?",
	"areSureCreateTournament": "Are your sure to create new Tournament?",
	"pair":"Pair",
	"category":"Category",
	"placeholderPair":"Insert your pair name",
	"placeholderCategory":"-- Select your category --",
	"observations":"Observations",
	"addObservation":"Add Observation",
	"addInscriptions":"Add Inscriptions",
	"addInscription":"Add Pair",
	"addCategory":"Add Category",
	"proposeGameChange":"Propose Change",
	"changeDate":"Change Date",
	"addScore":"Add Score",
	"modifyScore":"Modify Score",
	"date":"Date",
	"fromHour":"From Hour",
	"toHour":"To Hour",
	"available":"Available",
	"startDateNotSet":"Not Set",
	"showGameCalendarButton":"Show Calendar",
	"hideGameCalendarButton":"Hide Calendar",
	"closeTournamentGroupsButton":"Close Groups",
	"showDrawsButton":"Draws",
	"selectTypeOfDraw":"Select type of Draw",
	"newGroupTitle": "New Group",
	"ClickForAddGroup": "Click on + for add new group",
	"closeTournamentInscriptionButton": "Close Inscription",
	"closeTournamentInscriptionModalTitle": "Close Inscription",
	"AddTournamentInscriptionModalTitle": "Add Inscriptions",
	"totalInscriptionsTournament": "Total Inscripitons in Tournament:",
	"insertNumberOfPairsInEachGroup": "Insert number of pairs in each group",
	"errorNumberGroups": "All categories should be especified",
	"inscriptionsInThisCategory": "Inscriptions in this category",
	"viewTournamentInscriptionButton": "View Inscriptions",
	"viewTournamentInscriptionModalTitle": "View Inscriptions",
	"nameAndGroupDatesNotEmpty":"The name and groups dates can´t be empty",
	"categoryNotEmpty":"The tournament should be have almost one category",
	"noObservations":"Don´t have observations in this inscription",
	"observationsSomeEmptyFields":"All fields are mandatory",
	"someObservationsAreInvalid":"Some observations are invalid, there was not saved",
	"someCategoriesAreInvalid": "Some categories are invalid, there was not saved",
	"successMessageTextInscription":"Your inscription send succesfully",
	"successMessageDeleteInscription":"Your inscription has deleted succesfully",
	"successMessageTextObservations":"Your observations saved succesfully",
	"successMessageTournamentSaved":"Your tournament saved succesfully",
	"successMessageSaveScore":"Score added succesfully",
	"errorMessageTournamentSaved":"Yourn tournament has not saved",
	"errorMessageDeleteInscription":"Your inscription has not been deleted",
	"errorMessageGetPairsInfo":"Error getting pairs information", 
	"errorGroupsInvalid":"Groups are minimum 2 pairs please",
	"somePairsErroneusConfirm":"Some pairs have empty players, do you continue?",
	"notAddGroupsWhenSomeAreEmpty":"Can´t add groups when there are groups empty"
});