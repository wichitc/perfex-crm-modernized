# State Management Architecture - Perfex CRM

## State Layering Strategy

```text
STATE MANAGEMENT LAYERS
├── Server State: @tanstack/react-query (Query Client caching, invalidation, background refetch)
├── Local Form State: react-hook-form + zod resolver
├── Persistent Theme State: ThemeProvider Context + localStorage ("perfex_theme")
├── Session Auth State: useAuth hook + JWT HttpOnly Cookie / localStorage accessToken
└── UI State: React useState (Kanban vs Table view, Modal open/close state, Selected Invoice ID)
```
