# Accessibility Specifications (WCAG 2.1 AA) - Perfex CRM

## Accessibility Compliance Standards

1. **Keyboard Accessibility**: All interactive elements (buttons, links, form inputs, theme selectors, modal close icons) are navigable via `Tab` and `Shift+Tab`, with clear focus outline styles (`focus:ring-2 focus:ring-cyan-500`).
2. **Color Contrast**: Background to text contrast meets WCAG 2.1 AA standards (minimum 4.5:1 ratio for normal text, 3:1 for large headings).
3. **Screen Reader ARIA Attributes**: All dynamic modals use `role="dialog"`, `aria-modal="true"`, and descriptive `aria-label` tags.
4. **Form Labels & Error Messages**: Every input field includes associated `<label>` text and `aria-invalid` attributes upon validation error.
