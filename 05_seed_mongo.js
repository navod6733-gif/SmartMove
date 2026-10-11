// ============================================================
// SmartMove Transport Solutions — MongoDB Seed Script
// Run with:  mongosh smartmove 01_seed_mongo.js
// (or open mongosh, `use smartmove`, then `load('01_seed_mongo.js')`)
//
// NOTE ON CROSS-DATABASE REFERENCES:
// MongoDB documents reference Oracle rows by their numeric IDs
// (passenger_id, trip_id, route_id, vehicle_id, driver_id).
// The IDs below assume you ran 01_schema.sql + 02_sample_data.sql
// on a FRESH schema, where Oracle's IDENTITY columns start at 1
// and assign sequentially in insertion order — so these match the
// sample data exactly. If your IDs differ (e.g. you re-ran inserts,
// or deleted/re-added rows), look up the real ones first, e.g.:
//   SELECT passenger_id FROM passengers WHERE email = 'dilani.r@example.com';
//   SELECT trip_id FROM trips WHERE trip_date = DATE '2025-09-01'
//       AND route_id = (SELECT route_id FROM routes WHERE route_name = 'Colombo - Kandy Express');
// In the real app, PHP always writes the true Oracle ID it just
// fetched/inserted — this hardcoding is only for the seed demo data.
// ============================================================

use('smartmove');

// ------------------------------------------------------------
// Clean slate for repeatable seeding
// ------------------------------------------------------------
db.reviews.drop();
db.vehicle_documents.drop();
db.announcements.drop();
db.trip_media.drop();

// ------------------------------------------------------------
// COLLECTION: reviews
// Passenger reviews/feedback comments — the free-text half of
// Oracle's FEEDBACK table (linked back via oracle_feedback_id).
// ------------------------------------------------------------
db.reviews.insertMany([
  {
    oracle_feedback_id: 1,           // matches feedback.feedback_id in Oracle
    passenger_id: 1,                 // Dilani Rathnayake
    trip_id: 1,                      // Colombo-Kandy Express, 2025-09-01
    route_id: 1,
    route_name: "Colombo - Kandy Express",
    driver_id: 1,                    // Nimal Perera
    vehicle_id: 1,                   // SM-B-001
    rating: 5,
    comment: "Excellent service, the driver was very professional and the bus was spotless. Reached Kandy right on time.",
    tags: ["punctual", "clean", "friendly_driver"],
    created_at: new Date("2025-09-01T10:30:00Z"),
    reported: false
  },
  {
    oracle_feedback_id: 2,
    passenger_id: 2,                 // Ashan Peris
    trip_id: 1,
    route_id: 1,
    route_name: "Colombo - Kandy Express",
    driver_id: 1,
    vehicle_id: 1,
    rating: 4,
    comment: "Good trip overall, comfortable seats. Would be nice to have working AC throughout though.",
    tags: ["comfortable", "ac_issue"],
    created_at: new Date("2025-09-01T11:00:00Z"),
    reported: false
  },
  {
    oracle_feedback_id: 3,
    passenger_id: 6,                 // Lahiru Chandimal
    trip_id: 4,                      // Colombo-Galle Coastal, 2025-09-02
    route_id: 2,
    route_name: "Colombo - Galle Coastal",
    driver_id: 3,                    // Sunil Fernando
    vehicle_id: 3,                   // SM-B-003
    rating: 5,
    comment: "Beautiful coastal views and a smooth, safe ride. Sunil was an excellent, careful driver.",
    tags: ["scenic", "safe_driving"],
    created_at: new Date("2025-09-02T09:45:00Z"),
    reported: false
  },
  {
    oracle_feedback_id: 4,
    passenger_id: 7,                 // Harshani De Silva
    trip_id: 4,
    route_id: 2,
    route_name: "Colombo - Galle Coastal",
    driver_id: 3,
    vehicle_id: 3,
    rating: 2,
    comment: "Bus left 20 minutes late and the driver was rushing dangerously to make up time. Not a pleasant experience.",
    tags: ["late_departure", "unsafe_driving", "complaint"],
    created_at: new Date("2025-09-02T09:50:00Z"),
    reported: true
  },
  {
    oracle_feedback_id: 5,
    passenger_id: 11,                // Piumi Herath
    trip_id: 7,                      // Negombo-Colombo Commuter, 2025-09-01
    route_id: 3,
    route_name: "Negombo - Colombo Commuter",
    driver_id: 4,                    // Anura Jayasuriya
    vehicle_id: 4,                   // SM-V-001
    rating: 4,
    comment: "Reliable daily commute, van was on time as usual. Slightly cramped during peak hour.",
    tags: ["punctual", "crowded"],
    created_at: new Date("2025-09-01T08:00:00Z"),
    reported: false
  },
  {
    oracle_feedback_id: 6,
    passenger_id: 5,                 // Ishara Madushani
    trip_id: 12,                     // Colombo-Jaffna Overnight, 2025-09-04
    route_id: 4,
    route_name: "Colombo - Jaffna Overnight",
    driver_id: 2,                    // Kasun Silva
    vehicle_id: 2,                   // SM-B-002
    rating: 3,
    comment: "The overnight seats don't recline enough for comfortable sleep. Otherwise the trip was fine and safe.",
    tags: ["seat_comfort", "overnight"],
    created_at: new Date("2025-09-05T06:30:00Z"),
    reported: false
  },
  {
    oracle_feedback_id: 7,
    passenger_id: 7,                 // Harshani De Silva
    trip_id: 14,                     // Colombo-Nuwara Eliya Hill, 2025-09-06
    route_id: 5,
    route_name: "Colombo - Nuwara Eliya Hill",
    driver_id: 7,                    // Priyantha Kumara
    vehicle_id: 7,                   // SM-C-001
    rating: 5,
    comment: "Wonderful hill-country drive, very knowledgeable and friendly driver who pointed out great viewpoints.",
    tags: ["scenic", "friendly_driver", "knowledgeable"],
    created_at: new Date("2025-09-06T10:00:00Z"),
    reported: false
  },
  // A couple of extra reviews without a matching Oracle feedback row yet,
  // to show the collection can hold content ahead of/independent of Oracle sync
  {
    oracle_feedback_id: null,
    passenger_id: 9,                 // Sachini Weerasinghe
    trip_id: 9,                      // Negombo-Colombo Commuter, 2025-09-05
    route_id: 3,
    route_name: "Negombo - Colombo Commuter",
    driver_id: 4,
    vehicle_id: 4,
    rating: 1,
    comment: "Terrible experience — the van's brakes made a horrible grinding noise the whole trip. Feels unsafe, please inspect this vehicle urgently.",
    tags: ["safety_complaint", "mechanical_issue"],
    created_at: new Date("2025-09-05T08:10:00Z"),
    reported: true
  },
  {
    oracle_feedback_id: null,
    passenger_id: 3,                 // Nadeesha Kumari
    trip_id: 10,
    route_id: 3,
    route_name: "Negombo - Colombo Commuter",
    driver_id: 5,                    // Ruwan Gunawardena
    vehicle_id: 5,                   // SM-V-002
    rating: 4,
    comment: "Smooth ride, driver was courteous. Would appreciate more frequent departures during rush hour.",
    tags: ["courteous", "frequency_suggestion"],
    created_at: new Date("2025-09-08T07:40:00Z"),
    reported: false
  }
]);

// Text index enables the keyword-search query below
db.reviews.createIndex({ comment: "text", tags: "text" });
db.reviews.createIndex({ route_id: 1 });
db.reviews.createIndex({ driver_id: 1 });
db.reviews.createIndex({ vehicle_id: 1 });

// ------------------------------------------------------------
// COLLECTION: vehicle_documents
// Registration/insurance docs + photos per vehicle.
// ------------------------------------------------------------
db.vehicle_documents.insertMany([
  {
    vehicle_id: 1, vehicle_number: "SM-B-001", doc_type: "PHOTO",
    title: "Front view", url: "https://cdn.smartmove.lk/vehicles/SM-B-001/front.jpg",
    uploaded_at: new Date("2024-01-05T00:00:00Z")
  },
  {
    vehicle_id: 1, vehicle_number: "SM-B-001", doc_type: "INSURANCE",
    title: "Comprehensive insurance policy 2025", url: "https://cdn.smartmove.lk/vehicles/SM-B-001/insurance-2025.pdf",
    uploaded_at: new Date("2025-01-10T00:00:00Z"), expiry_date: new Date("2026-01-10T00:00:00Z")
  },
  {
    vehicle_id: 1, vehicle_number: "SM-B-001", doc_type: "INSPECTION",
    title: "Annual roadworthiness certificate", url: "https://cdn.smartmove.lk/vehicles/SM-B-001/inspection-2025.pdf",
    uploaded_at: new Date("2025-02-01T00:00:00Z"), expiry_date: new Date("2026-02-01T00:00:00Z")
  },
  {
    vehicle_id: 3, vehicle_number: "SM-B-003", doc_type: "PHOTO",
    title: "Interior seating", url: "https://cdn.smartmove.lk/vehicles/SM-B-003/interior.jpg",
    uploaded_at: new Date("2024-06-01T00:00:00Z")
  },
  {
    vehicle_id: 3, vehicle_number: "SM-B-003", doc_type: "REGISTRATION",
    title: "Vehicle registration certificate", url: "https://cdn.smartmove.lk/vehicles/SM-B-003/registration.pdf",
    uploaded_at: new Date("2021-05-20T00:00:00Z")
  },
  {
    vehicle_id: 4, vehicle_number: "SM-V-001", doc_type: "PHOTO",
    title: "Side view", url: "https://cdn.smartmove.lk/vehicles/SM-V-001/side.jpg",
    uploaded_at: new Date("2024-03-15T00:00:00Z")
  },
  {
    vehicle_id: 6, vehicle_number: "SM-M-001", doc_type: "INSPECTION",
    title: "Inspection report (transmission fault noted)", url: "https://cdn.smartmove.lk/vehicles/SM-M-001/inspection-2025-09.pdf",
    uploaded_at: new Date("2025-09-19T00:00:00Z"), expiry_date: new Date("2026-03-19T00:00:00Z")
  }
]);

db.vehicle_documents.createIndex({ vehicle_id: 1, doc_type: 1 });

// ------------------------------------------------------------
// COLLECTION: announcements
// Travel announcements & notifications.
// ------------------------------------------------------------
db.announcements.insertMany([
  {
    title: "Kandy route: temporary schedule change",
    body: "Due to road maintenance near Kadawatha, the 06:00 Colombo-Kandy Express may see a 15-20 minute delay through the end of September.",
    category: "SERVICE_DISRUPTION",
    route_id: 1, route_name: "Colombo - Kandy Express",
    published_at: new Date("2025-09-10T09:00:00Z"),
    expires_at: new Date("2025-09-30T23:59:59Z"),
    active: true
  },
  {
    title: "20% off Negombo-Colombo commuter tickets this week",
    body: "Book any weekday trip on the Negombo-Colombo Commuter route this week and get 20% off with promo code COMMUTE20.",
    category: "PROMOTION",
    route_id: 3, route_name: "Negombo - Colombo Commuter",
    published_at: new Date("2025-09-20T00:00:00Z"),
    expires_at: new Date("2025-09-27T23:59:59Z"),
    active: true
  },
  {
    title: "New route added: Colombo - Nuwara Eliya Hill",
    body: "We've launched a new daily service to Nuwara Eliya via Kandy. Book now for the scenic hill-country experience.",
    category: "GENERAL",
    route_id: 5, route_name: "Colombo - Nuwara Eliya Hill",
    published_at: new Date("2025-08-25T00:00:00Z"),
    expires_at: null,
    active: true
  },
  {
    title: "Vehicle SM-M-001 temporarily out of service",
    body: "SM-M-001 is undergoing transmission repairs and is out of rotation. Trips on affected routes have been reassigned to other vehicles.",
    category: "SERVICE_DISRUPTION",
    route_id: null, route_name: null,
    published_at: new Date("2025-09-20T12:00:00Z"),
    expires_at: new Date("2025-10-05T00:00:00Z"),
    active: true
  }
]);

db.announcements.createIndex({ active: 1, published_at: -1 });
db.announcements.createIndex({ route_id: 1 });

// ------------------------------------------------------------
// COLLECTION: trip_media
// Trip-related multimedia (photos/videos passengers or staff add).
// ------------------------------------------------------------
db.trip_media.insertMany([
  {
    trip_id: 4, route_id: 2, route_name: "Colombo - Galle Coastal",
    media_type: "PHOTO", url: "https://cdn.smartmove.lk/trips/4/coastline.jpg",
    caption: "Coastal view near Kalutara stop", uploaded_by: 6, uploaded_at: new Date("2025-09-02T08:30:00Z")
  },
  {
    trip_id: 14, route_id: 5, route_name: "Colombo - Nuwara Eliya Hill",
    media_type: "PHOTO", url: "https://cdn.smartmove.lk/trips/14/hillside.jpg",
    caption: "Misty hills en route to Nuwara Eliya", uploaded_by: 7, uploaded_at: new Date("2025-09-06T08:00:00Z")
  },
  {
    trip_id: 1, route_id: 1, route_name: "Colombo - Kandy Express",
    media_type: "VIDEO", url: "https://cdn.smartmove.lk/trips/1/onboard-clip.mp4",
    caption: "Onboard comfort walkthrough", uploaded_by: "system", uploaded_at: new Date("2025-09-01T06:05:00Z")
  }
]);

db.trip_media.createIndex({ trip_id: 1 });

print("Seed complete:");
print("  reviews: " + db.reviews.countDocuments());
print("  vehicle_documents: " + db.vehicle_documents.countDocuments());
print("  announcements: " + db.announcements.countDocuments());
print("  trip_media: " + db.trip_media.countDocuments());
