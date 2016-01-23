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

	const PasswordIncorrect = "Password Incorrect";
	const UserIncorrect = "User Incorrect";

	//TournamentController
	const TournamentNotFound = "Tournament not found";
	const CategoryNotFound = "Category not found";
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

	//AnnotationController
	const AnnotationNotFound = "Annotation not found";
	const AnnotationDeleted = "Annotation deleted";

	//CategoryController
	const PairNotFound = "Pair not found";

	//InscriptionController
	const InscriptionNotFound = "Inscription not found";
	const InscriptionDeleted = "Inscription deleted";
	const PairEmpty = "pair empty;";
	const CategoryEmpty = "category empty;";
	const CategoryIncorrect = "Error in select category";
	const PairDuplicate = "Pair duplicate";
	const Inscriptions = "Pairs registered succesfully";
	const StartDateEmpty = "startDate empty";
	const EndDateEmpty = "endDate empty";
	const AvailableEmpty = "available empty";
	const ObservationIncorrect = "observation incorrect";
	const TournamentInscriptionLimit = "tournament inscription limit";
	const CategoryInscriptionLimitMax = "category inscription limit";

	//ObservationController
	const ObservationNotFound = "Observation not found";
	const ObservationDeleted = "Observation deleted";

	//GroupController
	const GroupNotFound = "Group not found";
}
