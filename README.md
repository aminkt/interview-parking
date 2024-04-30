# Simple CLI parking application

This repository contains a simple CLI application written in PHP for an interview challenge. You can find a list of
topics here:

* [Install application](#install-application)
* [Run application](#run-application)
* [Run tests](#run-tests)
* [Assumptions and design decisions](#assumptions-and-design-decisions)
* [Architecture](#architecture)

## Install application

You have two options for installing the application: using Docker or installing PHP on your computer. Assuming you have
Docker and Docker Compose installed, you can proceed with the installation.

1. Clone the project on your local machine and navigate to cloned repository directory.
2. Run `docker compose up -d`
3. When running the application for the first time, You may need restart app container to load environment variables created by the installation script.


When you run the command `composer install`, all dependencies and libraries that are required for the project will be
installed automatically. Additionally, there is a composer `post-install-cmd` command in place which sets up the application
for the first time. This means that you don't have to worry about any manual setup processes.

Furthermore, when you run the `docker compose up` command for the first time, the `composer update` command will be
executed in the docker file. This enables us to build an image and push it to the docker registry if required, so you
don't have to clone the project. If this is the case, you can simply run the command `docker run app <image_name>` to
run application and then `docker exec app ./parking status` to use application.

## Run Application

There are several commands you can run to use the application. Run `docker compose exec app ./parking help` to see a
list of available commands:

```shell
Parking application:
--------------------
Available Commands:
1. install: Install application. 
        #sample: ./parking install
2. park: Park car in the parking. 
        Arguments:
                -p      [required] vehicleNumberPlate
                -t      [required] vehicleType
                -f      [optional] floorNumber
        #sample: ./parking park -p=43rer45 -t=Van -f=0
3. take-out: Take out car from the parking. 
        Arguments:
                -p      [required] vehicleNumberPlate
        #sample: ./parking take-out -p=43rer45
4. track: Track parking history of a vehicle. 
        Arguments:
                -p      [required] vehicleNumberPlate
        #sample: ./parking track -p=43rer45
5. status: See the Parking status. 
        #sample: ./parking status
6. vehicle-types: Get list of available vehicle types that can be handled by the application. 
        #sample: ./parking vehicle-types
```

As PHP is a scripting language, I prefer using this style of commands over an interactive shell. This is because every
time the script is run, the application is loaded from scratch due to PHP's behavior. Additionally, this style allows
you to use Linux tools such as grep and pipe your commands, which comes in handy for complex operations. For instance,
you can run the command `docker compose exec app ./parking status | grep Van` to view the floors where Van is parked.

## Run Tests

To run tests, simply follow the below steps:

1. Start the Docker containers; `docker compose up -d`

2. Run your tests; `docker compose exec app vendor/bin/phpunit test`

3. Stop the docker containers; `docker compose down`

## Assumptions and design decisions

I have noted down the following domain rules for the application:

1. The application should be designed to handle more than just one parking(ParkingEntity). However, for the first
   version, the Presentation layer is limited to working with only one parking.

2. Each parking has multiple floors (FloorEntity), with different capacity and restrictions on the types of vehicles
   that can be parked on each floor.

3. Only the aggregate root is allowed to park or remove vehicles to ensure consistency and atomic operations.

4. To park a vehicle, the application will require the vehicle's number plate, which is useful for tracking each car.
   This information is stored in the ReceiptEntity.

5. The App layer provides some queries to check the parking status, available vehicle types, and track each vehicle by
   its number plate.

> Using an Enum is preferred for defining available vehicle types due to their limited number and simplicity to add.

## Architecture

I am utilizing a clean architecture, Domain-Driven Design (DDD) approach in conjunction with the Command Query
Pattern to design the application. While other approaches such as Model-View-Controller (MVC) could also be used, I
decided to use this architecture for the purpose of this interview task.

I also use TDD (Test Driven Development) during the implementation phase which ensures that all parts I am developing
work well together and prevents over-engineering during the implementation. Additionally, you can write a testable code,
when you are testing it.
This approach saves a lot of time and makes interface design more practical.

Together, Clean Architecture and DDD offer a powerful approach to building software systems that are maintainable,
flexible, and aligned with the problem domain. Clean Architecture provides a structural blueprint for organizing the
codebase, while DDD offers principles and patterns for modeling and implementing the domain logic effectively.
Integrating both approaches can lead to well-designed, robust, and scalable software systems.

Using these architectures together provides
numerous benefits which are listed below:

### Command and Query

1. Separation of Concerns:
    * Commands: Commands represent actions that mutate the state of the application. By separating command processing
      from query processing, you can focus on modeling and handling commands without worrying about the complexities of
      querying data.
      Queries: Queries represent requests for data retrieval. Separating query handling allows you to optimize query
      performance and scalability independently from command processing.
    * Scalability: Independent Scaling: Since commands and queries are separate concerns, you can scale their processing
      independently based on the application's needs. For example, you can scale command processing to handle high write
      throughput while scaling query processing to handle high read throughput.

2. Performance Optimization:
    * Optimized Data Access: Queries can be optimized for read performance, such as through denormalization, caching, or
      using specialized data stores. Command processing can focus on maintaining consistency and enforcing business
      rules without being burdened by read optimization concerns.

3. Flexibility and Maintainability:
    * Evolutionary Design: Separating commands and queries facilitates evolutionary design by allowing each part of the
      system to evolve independently. Changes to command handling logic won't affect query processing, and vice versa.
    * Domain-Centric Modeling: Commands and queries can be modeled in a way that closely reflects the language and
      concepts of the domain, enhancing the maintainability and understandability of the system.

4. Consistency and Concurrency Control:
    * Concurrency Management: By separating commands and queries, you can implement concurrency control mechanisms
      tailored to each operation's requirements. For example, you may use optimistic concurrency control for queries and
      pessimistic concurrency control for commands.

5. Auditability and Debuggability:
    * Clear Intent: The separation of commands and queries makes the intent of each operation clear, improving
      auditability and debuggability. It's easier to trace the flow of commands that mutate the application state and
      queries that fetch data.

### Domain Driven Design

1. Shared Understanding: DDD encourages collaborative modeling sessions between domain experts and developers, leading
   to a shared understanding of the problem domain.
2. Domain-Centric Design: The focus on the domain leads to a design that closely reflects the language and concepts of
   the domain, making the software more intuitive and maintainable.
3. Complexity Management: DDD provides patterns and concepts to manage the complexity of large and complex domains, such
   as bounded contexts, aggregates, and domain events.
   > Note: We will discuss the domain model later. However, you can find ParkingEntity as the aggregate root of
   FloorEntity in the Domain layer.
4. Evolutionary Design: DDD supports iterative and incremental development, allowing the domain model to evolve over
   time as the understanding of the domain deepens or requirements change.

### Clean Architecture

1. Maintainability: The separation of concerns makes it easier to understand and modify individual components without
   affecting others.
2. Testability: The architecture encourages writing isolated unit tests for business logic without coupling to external
   dependencies.
3. Independence from Frameworks: The core business logic is decoupled from external frameworks, making it easier to
   switch or upgrade frameworks.
4. Flexibility: The architecture accommodates changes in requirements or technology stack without significant rework.

> Note: Due to implementing the Domain-Driven Design approach, I have removed the use case concept from the clean
> architecture and replaced it with commands and DDD entity design principles.

---

### Application Layers

Please check the `src` folder. The layers are separated into different directories, and each top layer depends on the
layers below it. They can access the layers below them. However, the Domain layer, located at the bottom of the list,
does not depend on other layers. With the help of the Dependency Inversion Principle, the dependencies of the Domain
layer have been inverted to make it independent.

1. **CLI**: This is the presentation layer that enables us to interact with the application using a shell.
2. **APP**: This is the application logic that uses command queries.
3. **Infra**: This is the infrastructure layer that is responsible for implementing domain interfaces and dependencies.
   Repositories and queue implementations can go here.
4. **Domain**: This is the layer that contains the business domain logics.

Please take note that there is a folder named `Unit` in the `test` directory. This folder contains unit tests and is
named this way to separate them from other kinds of tests like integration tests. Inside the `Unit` directory, you will
see the `src` directory pattern, which makes it easier to find where you should write tests for each class if required.
You may also find some comments in different directories to guide you on why I ignore some unit tests.

> In real projects, By using this directory pattern, I will write a script to check if all tests are written in a CI
> pipeline. This is helpful for code reviews.

> PHP does not support namespace visibility, which can cause problems for a team trying to work with a layered
> architecture. To ensure that architectural dependency limits are applied, we can use PHPCS or write a script in our CI
> pipeline to find any issues that violate dependency rules.

> I follow the below naming conventions:
> 1. **E**PascalCase for enums
> 2. **A**PascalCase for Abstract classes
> 3. **I**PascalCase for Interfaces
> 4. PascalCase for concrete classes
> 5. PHP Standards Recommendations (for namespaces, properties, methods, etc.)

> There are some comments in the code that describe additional details.


Hey, thanks a bunch for taking the time to read this document! :)

Amin Keshavarz