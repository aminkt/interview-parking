In software architecture, the division of responsibilities is critical to ensure the maintainability and scalability of
the system. The domain layer defines the rules and logic of the business domain, while the infra layer provides the
implementation details for the external interfaces, such as data storage, communication protocols, and user interfaces.

Specifically, in the context of repositories, the domain layer defines the interface that describes the operations that
can be performed on the repository, such as insert, update, delete, and select. The implementation of these operations
is done in the infra layer, which uses the appropriate technology stack, such as a database or a web service.

As for testing, the domain layer is responsible for unit testing the business logic, while the infra layer is
responsible for integration testing the external interfaces. Therefore, you won't find any tests in this particular code
section.