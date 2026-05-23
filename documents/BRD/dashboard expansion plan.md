# Implementation Plan - Advanced Dashboard Modules

Expanding the Siliwangi Rental Admin Dashboard with advanced operational, financial, and market intelligence features.

## Phase 1: Operational & Fleet Intelligence

- [x] **Document Expiry Alerts**: Add a dedicated widget to monitor STNK, Tax, and Insurance expiration dates.
- [x] **GPS Tracking Map**: Integrate a high-fidelity map placeholder (Leaflet.js) to simulate real-time fleet monitoring.
- [x] **Maintenance Schedule**: Add a list/grid of upcoming services for the next 30 days.
- [x] **Check-in/Out Tool**: Implement a Quick Action button that opens a modal for rapid vehicle inspection entry.

## Phase 2: Financial Strategy & CRM

- [x] **Profit & Loss Widget**: Create a calculation logic (Revenue - Maintenance - Driver Fees - Fuel) to display net profit.
- [x] **Midtrans Live Monitor**: Add a status board for real-time payment success/fail rates.
- [x] **Deposit Ledger**: Track security deposits held and those pending refund.
- [x] **Customer Blacklist**: Integrate a restricted user list to prevent fraud/damage.

## Phase 3: Business Intelligence & "Coming Soon" Modules

- [x] **Peak Season Predictor**: Implement a bar chart visualizing demand peaks based on historical data.
- [x] **Loyalty Program**: Add a premium glassmorphism card labeled "Coming Soon".
- [x] **Branch Performance**: Add a comparison chart with a "Coming Soon" overlay.
- [x] **WhatsApp Marketing**: Add a communication hub with a "Coming Soon" overlay.

## UI/UX Standards

- Use **Glassmorphism** for "Coming Soon" states to maintain premium feel.
- Implement **Leaflet.js** for mapping.
- Standardize on **Night Sky** color palette (Deep Navy #0F172A and Gold #D4AF37).

---

> [!IMPORTANT]
> Some features will be fully functional based on existing database columns, while others (like GPS) will start as visual demonstrations to be integrated with hardware APIs later.
