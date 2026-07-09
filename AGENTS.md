# Project Instructions

## General Approach

- Keep it simple. Less code means fewer bugs.
- Do only what was explicitly requested.
- Keep changes small and focused.
- Do not touch unrelated files, backend code, API contracts, or data flow unless the task clearly requires it.
- Prefer a simple fix at the call site over making shared helpers more complex.
- Ask before expanding the scope or changing an established contract.

## Code Style

- Prefer clear, direct code over clever abstractions.
- Use `const name = () => {}` for local functions in JavaScript and Vue files.
- Use Vue `defineModel` when it directly replaces `modelValue` / `update:modelValue` boilerplate.
- Avoid unnecessary emits, watchers, helpers, and abstractions.
- Build reusable components when a UI pattern is likely to be used in more than one place, but do not over-engineer them.

## Documentation

- Add useful documentation comments where they clarify intent, contracts, or non-obvious behavior.
- Use PHPDoc for PHP classes, methods, important array shapes, and service/controller contracts.
- Add JSDoc or short comments in JavaScript/Vue for reusable helpers, component models, emitted events, and non-obvious logic.
- Do not add comments that merely repeat what the code says.

## Frontend

- Keep components small and practical.
- Prefer reusable UI components for common controls such as pagination, filters, selects, labels, and form fields.
- Components should have simple contracts: clear props, `defineModel` where appropriate, and minimal emits.
- Avoid moving page-specific business logic into generic UI components.

## Verification

- Run the smallest relevant check for the changed code when possible.
- If a check cannot run because of the local environment, report the reason clearly.
