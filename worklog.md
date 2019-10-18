## Worklog

###### Solution made in Laravel.

Initial thoughts
- In REST api considering auth and anything else, i would consider users separated from accounts, as a landlord can have multiple homes / renters.
- For the company's business case, you have the ability to be managed and the user will be the manager and should have built in a impersonate feature i will not consider this.
- From experience with renting accounting systems, you usually have credited and debited types on transactions i will add that.
- Users, accounts and transactions should be scoped, so the wrong users and or accounts can't access data they are not allowed too.
- Most of the solution will be pretty straight forward my biggest concern is logging, which i consider as an audit log, to be able to traverse what users and or accounts have done.

