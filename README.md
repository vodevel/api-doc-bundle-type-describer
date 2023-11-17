# PoC

Describer to the [nelmio/api-doc-bundle](https://github.com/nelmio/NelmioApiDocBundle) using method signature (types)
## Install

```shell
composer require vodevel/api-doc-bundle-type-describer
```
## Usage

Unfortunately, it doesn't work without a ```Tag``` attribute yet, don't forget to add it.

Replacing
```php
class Controller {
    #[Route('api/example', methods: ['POST'])]
    #[RequestBody(content: new Model(type: ExampleRequest::class))]
    #[Response(
        response: 200,
        description: '',
        content: new Model(type: ExampleResponse::class),
    )]
    #[Tag(name: 'example')]
    public function exampleMethod(ExampleRequest $request): ExampleResponse {
        return new ExampleResponse();
    }
}
```
With
```php
class Controller {
    #[Route('api/special', methods: ['POST'])]
    #[Tag(name: 'example')]
    public function exampleMethod(ExampleRequest $request): ExampleResponse {
        return new ExampleResponse();
    }
}

#[RequestBody]
class SpecialPointRequest {}

#[Response]
class SpecialPointResponse {}
```