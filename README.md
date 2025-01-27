# Set API_KEY in .env :
API_KEY=your-secure-api-key

# Config your database url :
DATABASE_URL=

# Migrate to feed your Database
php bin/console doctrine:migrations:migrate

# Start the server backend :
symfony server:start

# Start the frontend dev :
npm run dev or npm run watch

# Start the frontend prod :
npm run build

# How to use :
- Frontend pet registration -> /
- Backend API call -> /api/pet/creation

# To test in Prod :
https://docupet.areauniverse.fr
