# LSMiniSocial — Instructions

## Requirements

- Docker Desktop
- Git

## Setup

**1. Clone the repository and enter the project folder.**

**2. Configure the environment files.**

In the root `.env` (Docker config), no changes needed — it works out of the box.

In `www/.env`, an OpenRouter API key is already configured and ready to use. If you want your own key, it must be from OpenRouter.

```
OPENROUTER_API_KEY=your_key_here
```

Get a free key at https://openrouter.ai

**3. Start the containers.**

**4. Run the database migrations.**

**5. Open the app.**

## AI Feature

The "Improve with AI" button is available when creating or editing a post. It sends the text to OpenRouter (Claude 3 Haiku) and returns an improved version. The user can accept or reject the suggestion before publishing.

This requires a valid `OPENROUTER_API_KEY` in `www/.env`.