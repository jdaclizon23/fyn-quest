# Users Feature Components

This folder contains components specific to the users feature.

## Components

Place user-specific components here that are:
- Used only within the users feature
- Not shared across other features
- Specific to user management functionality

## Examples

- `UserTable.vue` - User listing table
- `UserForm.vue` - User create/edit form
- `UserCard.vue` - User display card
- `UserFilters.vue` - User filtering component

## Usage

Import components in your user pages:

```vue
<script setup lang="ts">
import UserTable from './components/UserTable.vue';
</script>
```
