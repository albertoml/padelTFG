<?php

namespace PadelTFG\GeneralBundle\Resources\globals;

abstract class Literals
{
	//StatusController
	const StatusNotFound = "Status not found";

	//UserController
	const UserNotFound = "User not found";
	const UserDeleted = "User deleted";
	const EmailRegistered = "The email is registered";
	const EmptyContent = "Empty content";

	const NameEmpty = "name is Empty;";
	const LastNameEmpty = "lastName is Empty;";
	const EmailEmpty = "email is Empty;";
	const PasswordEmpty = "password is Empty;";

	//TournamentController
	const TournamentNotFound = "Tournament not found";
	const TournamentDeleted = "Tournament deleted";

	const AdminEmpty = "admin is Empty;";
	const CreationDateEmpty = "creationDate is Empty;";
	const AdminIncorrect = "Incorrect Admin";

	//SponsorController
	const SponsorNotFound = "Sponsor not found";
	const SponsorDeleted = "Sponsor deleted";
	const SponsorRegistered = "Sponsor registered";

	const CifEmpty = "CIF Empty;";

	//RecordalController
	const RecordalNotFound = "Recordal not found";
	const RecordalDeleted = "Recordal deleted";

	const TextEmpty = "text Empty;";
	const RecordalDateIncorrect = "recordal date incorrect;";
	const RecordalDateEmpty = "recordal date Empty;";

	//NotificationController
	const NotificationNotFound = "Notification not found";
	const NotificationDeleted = "Notification deleted";

	const NotificationDateIncorrect = "notification date incorrect;";
	const NotificationDateEmpty = "notification date Empty;";
	const TournamentEmpty = "tournament Empty;";
	const TournamentIncorrect = "Incorrect Tournament";


}