<?php

namespace PadelTFG\GeneralBundle\Resources\globals;

abstract class Literals
{
    //User Status
    const RegisteredUserStatus = "Registered";
    const In_TournamentUserStatus = "In Tournament";
    const Tournament_AdminUserStatus = "Tournament Admin";
    const DeletedUserStatus = "Deleted";
    
    //Tournament Status
    const CreatedTournamentStatus = "Created";
    const In_Inscription_DateTournamentStatus = "In Inscription Date";
    const In_Group_DateTournamentStatus = "In Group Date";
    const Matchs_DoneTournamentStatus = "Group phase (Matchs done)";
    const In_Finals_DateTournamentStatus = "In Finals Date";
    const FinishedTournamentStatus = "Finished";
    const DeletedTournamentStatus = "Deleted";

    //Sponsor Status
    const ActiveSponsorStatus = "Active";
    const DefaulterSponsorStatus = "Defaulter";
    const HiddenSponsorStatus = "Hidden";
    const DeletedSponsorStatus = "Deleted";

    //Recordal Status
    const CreatedRecordalStatus = "Created";
    const ReadRecordalStatus = "Read";
    const HiddenRecordalStatus = "Hidden";
    const DeletedRecordalStatus = "Deleted";

	//Notification Status
    const CreatedNotificationStatus = "Created";
    const SentNotificationStatus = "Sent";
    const ReadNotificationStatus = "Read";
    const HiddenNotificationStatus = "Hidden";
    const DeletedNotificationStatus = "Deleted";

    //Game Status
    const CreatedGameStatus = "Created";
    const In_Process_To_ChangeGameStatus = "In Process To Change";
    const PendingGameStatus = "Pending";
    const PlayedGameStatus = "Played";
    const WonGameStatus = "Won";
    const LosedGameStatus = "Losed";
    
    //Annotation Status
    const CreatedAnnotationStatus = "Created";
    const ReadAnnotationStatus = "Read";
    const HiddenAnnotationStatus = "Hidden";
    const DeletedAnnotationStatus = "Deleted";

    //Inscription Status
    const Tournament_Not_StartedInscriptionStatus = "Tournament Not Started";
    const Not_ClassifiedInscriptionStatus = "Not Classified";
    const ClassifiedInscriptionStatus = "Classified";
    const FinishedInscriptionStatus = "Finished";

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
	const UserDuplicate = "User duplicate";
	const Inscriptions = "Pairs registered succesfully";
	const StartDateEmpty = "startDate empty";
	const EndDateEmpty = "endDate empty";
	const AvailableEmpty = "available empty";
	const ObservationIncorrect = "observation incorrect";
	const TournamentInscriptionLimit = "tournament inscription limit";
	const CategoryInscriptionLimitMax = "category inscription limit";

	const IncorrectGender = "Incorrect inscription due to pair gender";

	//ScheduleController
	const ScheduleNotFound = "Schedule not found";

	//Genders
	const GenderMale = "Male";
	const GenderFemale = "Female";
	const GenderMixed = "Mixed";

	//ObservationController
	const ObservationNotFound = "Observation not found";
	const ObservationDeleted = "Observation deleted";

	//GroupController
	const GroupNotFound = "Group not found";
	const NewGroupLabel = "New Group";

	//GameController
	const GameNotFound = "Game not found";
	const TournamentIdNotCorrect = "Tournament ID not correct";
}
