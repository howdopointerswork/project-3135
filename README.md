# project-3135


## General Algorithm:

	- User will be at login screen according to cookies/session
	- Upon authentication/authorization, go to dashboard
	- From dashboard, users can book appointments/check calendar, view their personal logs, find medical records, or set up the Monitor AI system that alerts the user when certain criteria is met (according to object, subject, and environment attributes)


## Systems:
	- Enter caloric intake, blood pressure, etc. - Logging System
	- Warn/inform user based on statistical means, variances, etc. - Alert System
	- Dashboard page for user-specific data and interaction - Dashboard
	- Reference monitor for DB handling and checking authorization - RM System
	- Appointment/Booking system - use of calendar to track - Booking System
	- Find public records pertaining to Health ID - Record/Search System
	- System manager for bringing everything together - System Manager



## Alert System
	- Get a code based on monitor AI 
	- Render pop ups for alerts
	- Process redirections


## Booking System


## Record/Search System
	- User can search for records and entries based on health ID and other attributes
	- Users can save these records from the db to their own personal folder
	



## Logging System
	- Select a date to add activities to
	- Activities will display a pop up with fillable fields, that are submitted via Reference Monitor
	- Activities stored in user array that can be written/read from JSON and use hashing for ease of use
	- Activities should be accessed by index or date
	- When dates are clicked again, added info should be displayed
	- Can sort activities by filter




## Reference Monitor
	- Use JSON + hashing for activity and booking reading/writing
	- Use htmlspecialchars() for security
	- Access should be Least Privilege / Zero-Tolerance
	- Use of cookies and complete mediation
	- Supports CRUD and should follow ACID principles




# Classes

## User
	- UserID [int]
	- Username [string]
	- Email [string]
	- Password [string] (hashed)
	- Height [int]
	- Weight [int]
	- Age [int]
	- AccessLvl [int] //access level
	- HealthID [int]
	
	

## Alert
	- AlertID [int]
	- Message [string]

	+ redirect()
	

## Logging
	- Activity [class object]
	- Calories [int]
	- Sleep [int]
	- Water intake [int]
	

## Booking
	- BookingID [int]
	- Date [string?]
	- Note [string] - summary of booking
	

## Reference Monitor
	
	- current [User] //currently logged in user

	+ Authenticate(string username, string password)
	+ AddUser(user u)
	+ UpdateUser(int category, object value)


