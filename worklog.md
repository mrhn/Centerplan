## Worklog

###### Solution made in Laravel.

##### Initial thoughts
- In REST api considering auth and anything else, i would consider users separated from accounts, as a landlord can have multiple homes / renters.
- For the company's business case, you have the ability to be managed and the user will be the manager and should have built in a impersonate feature i will not consider this.
- From experience with renting accounting systems, you usually have credited and debited types on transactions i will add that.
- Users, accounts and transactions should be scoped, so the wrong users and or accounts can't access data they are not allowed too.
- Most of the solution will be pretty straight forward my biggest concern is logging, which i consider as an audit log, to be able to traverse what users and or accounts have done.

##### Developmment thoughts
- Users and Accounts should be a many to many relationship, since there can be multiple owners of apartments. Also if we manage their accounts, it would be nice both parties could access them.
- Transaction date could be done at another time then creation (async), so i would not think created_at updated_at is sufficient.
- Transaction amount is a double for now, but normally if you would do these kind of money criticial system, an integeger representing the full value would be better. Think how bitcoin and google adwords bids work. So we would never loose number precision.
- Some design choices like services is thinking about bigger scale projects, as right now wrapping Model::delete(1) in a service is quite overkill. But for bigger projects where you need to have similar logics in commands, jobs and controllers quite needed.
- Going with a jsend style error response and simple array responses for the api.

##### Github issues
- [Model migration issue](https://github.com/mrhn/Centerplan/issues/1)
- [CRUD routes](https://github.com/mrhn/Centerplan/issues/2)
- [Auth](https://github.com/mrhn/Centerplan/issues/3)
