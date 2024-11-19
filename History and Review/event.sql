-- Creating the Events Table
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255),         -- Image path for event cover photo
    category VARCHAR(100),           -- Category of the event
    organized_by VARCHAR(100),       -- Organizer of the event
    past_event_photos TEXT          -- Comma-separated paths for past event photos
);

-- Creating the Reviews Table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    participant_name VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Inserting Sample Data into Events Table
INSERT INTO events (event_name, event_date, description, image_path, category, organized_by, past_event_photos) VALUES
('Beach Cleanup Day', '2024-07-15', 
 'Join us for a day of fun and community service at the beach. Help keep our shores clean and enjoy refreshments.', 
 'images/beachcleanup.png', 
 'Community Service', 
 'Beach Volunteers', 
 'images/beachcleanup1.jpg,images/beachcleanup2.jpg,images/beachcleanup3.png,images/beachcleanup4.jpg,images/beachcleanup5.jpg'),

('Music Festival', '2024-08-20', 
 'A weekend filled with live music from local bands. Food trucks and artisan vendors will be present.', 
 'images/musicfestival.png', 
 'Entertainment', 
 'City Music Events', 
 'images/musicfestival1.jpg,images/musicfestival2.jpg,images/musicfestival3.jpg,images/musicfestival4.jpg,images/musicfestival5.jpg'),

('Art in the Park', '2024-09-10', 
 'Experience local art and meet the artists. Family-friendly activities and workshops will be available.', 
 'images/artinthepark.jpg', 
 'Art & Culture', 
 'Art Community', 
 'images/artinthepark1.jpeg,images/artinthepark2.jpg,images/artinthepark3.jpeg,images/artinthepark4.jpg,images/artinthepark5.jpeg'),

('Community Yoga', '2024-10-05', 
 'Join us for a relaxing yoga session in the park. Open to all levels. Bring your mat!', 
 'images/communityyoga.jpg', 
 'Health & Wellness', 
 'Local Yoga Club', 
 'images/yoga1.jpg,images/yoga2.jpg,images/yoga3.jpg,images/yoga4.jpg,images/yoga5.jpg'),

('Harvest Festival', '2024-11-15', 
 'Celebrate the harvest season with local produce, crafts, and family activities.', 
 'images/harvestfestival.jpg', 
 'Festival', 
 'Community Farmers', 
 'images/harvest1.jpg,images/harvest2.jpg,images/harvest3.jpg'),

('Winter Wonderland', '2024-12-20', 
 'A holiday event featuring ice skating, hot cocoa, and festive lights. Fun for the whole family!', 
 'images/winterwonderland.jpg', 
 'Holiday Celebration', 
 'City Events Committee', 
 'images/winter1.jpeg,images/winter2.jpg,images/winter3.jpeg,images/winter4.jpg,images/winter5.jpg');

-- Inserting Sample Data into Reviews Table
INSERT INTO reviews (event_id, participant_name, review_text) VALUES
(1, 'Alice Johnson', 'It was a great experience! The beach looks so much cleaner now.'),
(1, 'Bob Smith', 'Loved being part of the cleanup. Will definitely join again next year!'),
(2, 'Charlie Brown', 'Fantastic music and a lively atmosphere! Really enjoyed it.'),
(2, 'Daisy Miller', 'The food trucks were amazing! Great selection of food.'),
(3, 'Eva Green', 'A beautiful day spent in the park with wonderful art.'),
(4, 'Frank White', 'Such a calming yoga session. I feel refreshed!'),
(5, 'Grace Lee', 'The festival was delightful. I loved the local produce stalls.'),
(6, 'Hank Turner', 'The ice skating was magical! A wonderful way to celebrate the season.');
