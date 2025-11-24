# FBA Shipping Demo

A minimal demo for shipping an order via an FBA adapter. Includes domain models, use case orchestration, Twig-based web demo, unit tests, and Docker setup for easy run.

## Stack
- PHP 8.2+
- Twig 3 (presentation)
- PHPUnit, PHPStan, PHPCS (dev tools)
- Docker (optional run)

## Project layout
- `src/Domain/Shipping/ShippingServiceInterface.php` — domain port for shipping.
- `src/Data/Order`, `src/Data/Buyer` — domain models with validation.
- `src/Service/` — FBA adapter pieces: payload builder, DTOs, client stub, shipping service.
- `src/UseCase/ShipOrder.php` — application use case orchestrating repositories and shipping port.
- `src/Repository/Mock*Repository.php` — mock data loaders from `mock/` JSON.
- `src/Presentation/` — Bootstrap/Twig controller and view model for the demo UI.
- `templates/demo.html.twig` — Bootstrap 5.3 demo page.
- `tests/` — unit tests covering domain, adapter, use case, controller.

## Setup
Install dependencies:
```bash
composer install
```
Run static analysis / coding standards / tests:
```bash
composer analyse
composer lint
composer test
```

## Run the demo (local)
Start the built-in server:
```bash
composer serve
```
Open http://localhost:8000 and submit the form (defaults use provided mocks: order 16400, buyer 29664). You’ll get a generated tracking number or a friendly error.

## Run with Docker
Build and start:
```bash
docker compose up --build
```
Then open http://localhost:8000. Image uses `php:8.2-cli-alpine` and runs `php -S` with the demo front controller.

## Notes
- Error handling: domain exceptions carry internal messages and user-friendly texts; UI shows only the friendly messages.
- The FBA client is a stub that hashes the payload into a deterministic tracking number; swap `StubFbaClient` with a real client to integrate with FBA.
- Mock data lives under `mock/`; repositories/loaders are separated for easy replacement with real data sources.
- Optional SP-API mock: set `USE_SPAPI_MOCK=1` to use `MockSpApiClient`, which builds an SP-API request blueprint and returns a deterministic tracking number without real credentials.
