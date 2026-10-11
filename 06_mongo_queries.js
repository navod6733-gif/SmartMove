// ============================================================
// SmartMove Transport Solutions — MongoDB Required Queries
// Run with: mongosh smartmove 06_mongo_queries.js
// Each query below is also usable as-is from PHP's mongodb driver.
// ============================================================

use('smartmove');

print("\n=== 1. All passenger reviews for a specific route (Colombo - Kandy Express) ===");
db.reviews.find(
  { route_id: 1 },
  { comment: 1, rating: 1, passenger_id: 1, created_at: 1, _id: 0 }
).sort({ created_at: -1 }).forEach(printjson);


print("\n=== 2a. Highest-rated DRIVERS based on customer feedback ===");
db.reviews.aggregate([
  { $group: {
      _id: "$driver_id",
      avg_rating: { $avg: "$rating" },
      reviews_count: { $sum: 1 }
  }},
  { $match: { reviews_count: { $gte: 1 } } },
  { $sort: { avg_rating: -1, reviews_count: -1 } },
  { $project: { driver_id: "$_id", avg_rating: { $round: ["$avg_rating", 2] }, reviews_count: 1, _id: 0 } }
]).forEach(printjson);

print("\n=== 2b. Highest-rated VEHICLES based on customer feedback ===");
db.reviews.aggregate([
  { $group: {
      _id: "$vehicle_id",
      avg_rating: { $avg: "$rating" },
      reviews_count: { $sum: 1 }
  }},
  { $sort: { avg_rating: -1, reviews_count: -1 } },
  { $project: { vehicle_id: "$_id", avg_rating: { $round: ["$avg_rating", 2] }, reviews_count: 1, _id: 0 } }
]).forEach(printjson);


print("\n=== 3. Search complaints/feedback by keyword (e.g. 'brakes', 'late') ===");
print("-- text-index search for 'brakes':");
db.reviews.find(
  { $text: { $search: "brakes" } },
  { comment: 1, rating: 1, route_name: 1, score: { $meta: "textScore" }, _id: 0 }
).sort({ score: { $meta: "textScore" } }).forEach(printjson);

print("-- regex search (case-insensitive) for 'late' across comments, useful for partial words:");
db.reviews.find(
  { comment: { $regex: "late", $options: "i" } },
  { comment: 1, rating: 1, route_name: 1, _id: 0 }
).forEach(printjson);

print("-- all reviews explicitly flagged as complaints (reported = true):");
db.reviews.find(
  { reported: true },
  { comment: 1, rating: 1, route_name: 1, _id: 0 }
).forEach(printjson);


print("\n=== 4. Vehicle documents and multimedia for a specific vehicle (SM-B-001) ===");
print("-- documents:");
db.vehicle_documents.find(
  { vehicle_number: "SM-B-001" },
  { doc_type: 1, title: 1, url: 1, uploaded_at: 1, expiry_date: 1, _id: 0 }
).forEach(printjson);

print("-- trip multimedia for trips run on this vehicle's route (route_id 1):");
db.trip_media.find(
  { route_id: 1 },
  { media_type: 1, url: 1, caption: 1, uploaded_at: 1, _id: 0 }
).forEach(printjson);


print("\n=== Bonus: active announcements right now ===");
db.announcements.find(
  { active: true, $or: [ { expires_at: null }, { expires_at: { $gte: new Date() } } ] },
  { title: 1, category: 1, published_at: 1, expires_at: 1, _id: 0 }
).sort({ published_at: -1 }).forEach(printjson);
