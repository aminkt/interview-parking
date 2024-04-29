Hello there,

It's great to see you here!

In my opinion, writing unit tests for the presentation layer (CLI or Web) is not a good idea. The presentation layer changes frequently and is tightly coupled with the presentation technology, such as JavaScript frameworks or other presentation tools. Tests for this layer are typically categorized as integration tests. It's okay to test some helper classes here, but for REST APIs or CLI outputs, it's better to place tests in the frontend side to ensure code correctness.

Instead of writing unit tests for errors, it's a better idea to log them and take care of them. This is because if you have written tests for the underlying layers, this layer will simply present data, and small errors usually surface. Additionally, this layer can be easily tested with automated QA tools to ensure that the response is correct.