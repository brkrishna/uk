# Udyog Kranthi Web portal

## Roles

anonymous	- Casual visitor
User - Registered user, not subscribed to any content yet
Subscriber - Paid user, subscribed to content 
Editor - Content creator, uploads content, tags with notes, replies to questions posted by susbcribers/user
Accounts - Maintains subscriptions, follow up on payments
Admin	- super user / god

## Features

### Publications
Lists the various publications hosted on this portal like Magazines, GO's and Special Editions
Type, Name, Description, Frequency, unit price

### Site content
The actual content against any publication with notes describing the content, attachment, keywords and dates
Content Type, Publication, Attachment, Keywords, Volume/GO, Title, Notes, Date of publication, Start and End dates to be visible on portal

### Subscription models
Subscription models offered for various publications
Publication, Frequency, Duration, Offer price

### Subscriptions
Registered users mapped against various subscription models 
UserID, Publication, Frequency, Duration, Amount, Paid, ValidFrom, ValidTo

### Queries
Users should be able to post questions and seek clarifications
UserID, Question, PostedOn, Answer, AnsweredOn, Status (Open, Answered, Archived)

## Use cases	
As an anonymous visitor I should be able to understand what this site is all about and probably drive towards signing up
As a registered user I should be able to change password, evaluate subscription options, subscribe, post queries
As a subscriber	should be able to perform all functions as a user, should also be able to view my subscription details, access content subscribed for, download content
As an editor I should be able to upload content, tag for search, answer queries
As an accounts person I should be able track subscriptions, follow up for renewals
As an admin I should	maintain different users
