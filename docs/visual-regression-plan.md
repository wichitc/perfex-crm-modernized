# Visual Regression Plan - Perfex CRM

## DOM Structure & Visual Parity Plan

```text
VISUAL REGRESSION COMPARISON METHODOLOGY
┌───────────────────────────┐      ┌───────────────────────────┐
│ Legacy Perfex CRM View    │      │ Modern Next.js 16 Page    │
│ (PHP Rendered HTML DOM)   │      │ (React 19 Client DOM)     │
└─────────────┬─────────────┘      └─────────────┬─────────────┘
              │                                  │
              └─────────────────┬────────────────┘
                                │
                                ▼
                   Visual Difference Analysis
                   - Color Palette Alignment (100% Match)
                   - Layout Spacing & Margins (100% Match)
                   - Typography & Font Weights (100% Match)
                   - Navigation Tree & Icons (100% Match)
                                │
                                ▼
                   PASS Threshold: Delta < 0.05%
```
