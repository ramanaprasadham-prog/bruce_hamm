<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Describes everything that can be edited from a page's edit screen.
 *
 * Page body text (Home, Biography, Lessons) comes from the normal page editor.
 * Everything else — photos, tracks, videos, headings — is described here and shown
 * as boxes under the editor. Each default below is exactly what the original
 * design shipped with, so a page looks the same until someone changes it.
 */

function bruce_hamm_page_headers()
{
    return [
        'biography' => [
            'Disciple · Performer · Teacher',
            'A lifelong journey through North Indian classical music under the guidance of a legendary maestro.',
        ],
        'gallery' => [
            'Moments in Music',
            'Performance photos and archival images from decades on stage.',
        ],
        'music' => [
            'Recordings',
            'Listen to performances and recordings of North Indian classical Sarod.',
        ],
        'videos' => [
            'On Stage',
            'Watch breathtaking performances and concert footage directly from their channels.',
        ],
        'events' => [
            'Live Performances',
            'Concerts, appearances, and performances — upcoming and past.',
        ],
        'lessons' => [
            'Study Sarod',
            'Bruce teaches anyone willing to dedicate themselves to the art of North Indian classical music.',
        ],
        'contact' => [
            'Get in Touch',
            'Reach out for performances, lessons, or general inquiries.',
        ],
    ];
}

/** Pages whose main text is written in the normal editor. All others hide the editor. */
function bruce_hamm_pages_with_editor()
{
    return ['home', 'biography', 'lessons'];
}

function bruce_hamm_photo_rows($rows)
{
    $out = [];
    foreach ($rows as $row) {
        $out[] = array_merge(['caption' => '', 'note' => '', 'wide' => '', 'shape' => 'standard'], $row);
    }
    return $out;
}

function bruce_hamm_field_schema()
{
    static $schema = null;
    if (null !== $schema) {
        return $schema;
    }

    $a = 'bruce_hamm_asset';

    $header_box = function ($slug) {
        $header = bruce_hamm_page_headers()[$slug];
        return [
            'id' => 'page_header',
            'title' => 'Page heading',
            'intro' => 'The big title at the top of this page is the page title above. These two lines sit just above and below it.',
            'fields' => [
                ['key' => 'eyebrow', 'label' => 'Small line above the title', 'type' => 'text', 'default' => $header[0]],
                ['key' => 'subtitle', 'label' => 'Line below the title', 'type' => 'textarea', 'default' => $header[1]],
            ],
        ];
    };

    $slide_fields = [
        ['key' => 'image', 'label' => 'Photo', 'type' => 'image', 'default' => ''],
        ['key' => 'alt', 'label' => 'Photo description (for screen readers)', 'type' => 'text', 'role' => 'alt', 'default' => ''],
    ];

    $album_fields = function ($n, $heading, $cover, $cover_alt, $tracks) {
        return [
            'id' => 'album' . $n,
            'title' => $heading,
            'intro' => 'Add the album cover, then each track. Upload an MP3 with "Choose audio file". Tracks without an audio file are listed under the "coming soon" label instead of getting a player.',
            'fields' => [
                ['key' => 'album' . $n . '_cover', 'label' => 'Album cover', 'type' => 'image', 'default' => $cover],
                ['key' => 'album' . $n . '_cover_alt', 'label' => 'Cover description (for screen readers)', 'type' => 'text', 'role' => 'alt', 'default' => $cover_alt],
                [
                    'key' => 'album' . $n . '_tracks',
                    'label' => 'Tracks',
                    'type' => 'repeater',
                    'item_label' => 'title',
                    'add_label' => 'Add track',
                    'default' => $tracks,
                    'fields' => [
                        ['key' => 'title', 'label' => 'Track title', 'type' => 'text', 'default' => ''],
                        ['key' => 'meta', 'label' => 'Subtitle (or length, for tracks without audio)', 'type' => 'text', 'default' => ''],
                        ['key' => 'audio', 'label' => 'Audio file (MP3)', 'type' => 'audio', 'default' => ''],
                    ],
                ],
            ],
        ];
    };

    $schema = [
        'home' => [
            [
                'id' => 'home_hero',
                'title' => 'Home page headline',
                'intro' => 'The paragraph under the headline is the main text of this page, edited in the editor above.',
                'fields' => [
                    ['key' => 'hero_eyebrow', 'label' => 'Small line above the headline', 'type' => 'text', 'default' => 'North Indian Classical Music'],
                    ['key' => 'hero_line1', 'label' => 'Headline, first line', 'type' => 'text', 'default' => 'The Art of'],
                    ['key' => 'hero_line2', 'label' => 'Headline, second line (gold)', 'type' => 'text', 'default' => 'Sarod and Sitar'],
                    ['key' => 'hero_button1', 'label' => 'First button text (links to Biography)', 'type' => 'text', 'default' => 'Explore Biography'],
                    ['key' => 'hero_button2', 'label' => 'Second button text (links to Lessons)', 'type' => 'text', 'default' => 'Book a Lesson'],
                    [
                        'key' => 'hero_stats',
                        'label' => 'Highlight numbers',
                        'type' => 'repeater',
                        'item_label' => 'label',
                        'add_label' => 'Add highlight',
                        'default' => [
                            ['value' => '1974', 'label' => 'Began Study'],
                            ['value' => '40+', 'label' => 'Years of Mastery'],
                            ['value' => '∞', 'label' => 'Legacy Continues'],
                        ],
                        'fields' => [
                            ['key' => 'value', 'label' => 'Big number or symbol', 'type' => 'text', 'default' => ''],
                            ['key' => 'label', 'label' => 'Caption', 'type' => 'text', 'default' => ''],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'home_slides',
                'title' => 'Home page photo slideshow',
                'intro' => 'These photos rotate automatically at the top of the home page. Drag rows to change the order.',
                'fields' => [
                    [
                        'key' => 'hero_slides',
                        'label' => 'Photos',
                        'type' => 'repeater',
                        'item_label' => 'alt',
                        'add_label' => 'Add photo',
                        'fields' => $slide_fields,
                        'default' => [
                            ['image' => $a('images/homepage-bg/Bruce_2017_GMC.jpg'), 'alt' => 'Bruce Hamm with sarod, 2017'],
                            ['image' => $a('images/homepage-bg/Bruce_Kolkata_2019.jpg'), 'alt' => 'Bruce Hamm performing in Kolkata, 2019'],
                            ['image' => $a('images/homepage-bg/Bruce_Baba_1.jpg'), 'alt' => 'Bruce Hamm performing sarod with tabla and tanpura'],
                            ['image' => $a('images/homepage-bg/Doordharshan_Mix_3.jpg'), 'alt' => 'Bruce Hamm performing with ensemble on stage'],
                        ],
                    ],
                ],
            ],
        ],

        'biography' => [
            $header_box('biography'),
            [
                'id' => 'bio_photos',
                'title' => 'Biography side photos',
                'intro' => 'Photos shown beside the biography text. The biography text itself is written in the editor above: each section is a "Biography section" block (add more from the + button, under Patterns).',
                'fields' => [
                    [
                        'key' => 'bio_photos',
                        'label' => 'Photos',
                        'type' => 'repeater',
                        'item_label' => 'alt',
                        'add_label' => 'Add photo',
                        'fields' => [
                            ['key' => 'image', 'label' => 'Photo', 'type' => 'image', 'default' => ''],
                            ['key' => 'alt', 'label' => 'Photo description (for screen readers)', 'type' => 'text', 'role' => 'alt', 'default' => ''],
                            ['key' => 'caption', 'label' => 'Caption', 'type' => 'textarea', 'default' => ''],
                            ['key' => 'note', 'label' => 'Second caption line (shown in gold)', 'type' => 'text', 'default' => ''],
                            ['key' => 'wide', 'label' => 'Show the whole photo, uncropped', 'type' => 'checkbox', 'default' => ''],
                        ],
                        'default' => bruce_hamm_photo_rows([
                            [
                                'image' => $a('images/gallery/alladuin-fes-aak.jpg'),
                                'alt' => 'George Ruckert, Peter Van Gelder, Ali Akbar Khan, James Pomerantz, Swapan Chaudhuri, Terence Pease, and Bruce Hamm at the 1st Annual Allaudin Khan Festival, 1991',
                                'caption' => 'Left to Right: George Ruckert, Peter Van Gelder, Ali Akbar Khan, James Pomerantz, Swapan Chaudhuri, Terence Pease, Bruce Hamm.',
                                'note' => '1st Annual Allaudin Khan Festival, 1991',
                            ],
                            [
                                'image' => $a('images/gallery/doordharshan-ensemble.jpg'),
                                'alt' => 'Ensemble performance on Doordarshan with sarod, tanpura, and tabla',
                                'caption' => 'Left to Right: Ali Akbar Khan, Buddhdev Dasgupta, Dhyanesh Khan, Supriyo Banerjee, Bruce Hamm.',
                                'wide' => '1',
                            ],
                        ]),
                    ],
                ],
            ],
            [
                'id' => 'bio_highlights',
                'title' => 'Biography highlights list',
                'fields' => [
                    ['key' => 'highlights_title', 'label' => 'Heading', 'type' => 'text', 'default' => 'Highlights'],
                    [
                        'key' => 'highlights',
                        'label' => 'Highlights',
                        'type' => 'repeater',
                        'item_label' => 'text',
                        'add_label' => 'Add highlight',
                        'fields' => [
                            ['key' => 'text', 'label' => 'Text', 'type' => 'text', 'default' => ''],
                            ['key' => 'link_text', 'label' => 'Linked words after the text (optional)', 'type' => 'text', 'default' => ''],
                            ['key' => 'link_url', 'label' => 'Link address for those words (optional)', 'type' => 'url', 'default' => ''],
                        ],
                        'default' => [
                            ['text' => 'Disciple of Ustad Ali Akbar Khan', 'link_text' => '', 'link_url' => ''],
                            ['text' => 'Instructor, Ali Akbar College of Music', 'link_text' => '', 'link_url' => ''],
                            ['text' => 'Solo concerts across US & India', 'link_text' => '', 'link_url' => ''],
                            ['text' => 'Founder of', 'link_text' => 'Path of Raga Foundation', 'link_url' => 'https://pathofraga.org'],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'bio_cta',
                'title' => 'Biography call-to-action band',
                'fields' => [
                    ['key' => 'cta_title', 'label' => 'Heading', 'type' => 'text', 'default' => 'Experience the music live'],
                    ['key' => 'cta_text', 'label' => 'Text', 'type' => 'text', 'default' => 'Explore upcoming events or inquire about private lessons.'],
                    ['key' => 'cta_button1', 'label' => 'First button text (links to Events)', 'type' => 'text', 'default' => 'View Events'],
                    ['key' => 'cta_button2', 'label' => 'Second button text (links to Lessons)', 'type' => 'text', 'default' => 'Learn Sarod and Sitar'],
                ],
            ],
        ],

        'gallery' => [
            $header_box('gallery'),
            [
                'id' => 'gallery_photos',
                'title' => 'Gallery photos',
                'intro' => 'Add, remove or drag photos into a new order. "Shape" controls how the photo is cropped and whether it spans the full width.',
                'fields' => [
                    [
                        'key' => 'gallery_photos',
                        'label' => 'Photos',
                        'type' => 'repeater',
                        'item_label' => 'alt',
                        'add_label' => 'Add photo',
                        'fields' => [
                            ['key' => 'image', 'label' => 'Photo', 'type' => 'image', 'default' => ''],
                            ['key' => 'alt', 'label' => 'Photo description (for screen readers)', 'type' => 'text', 'role' => 'alt', 'default' => ''],
                            ['key' => 'caption', 'label' => 'Caption (optional)', 'type' => 'text', 'default' => ''],
                            [
                                'key' => 'shape',
                                'label' => 'Shape',
                                'type' => 'select',
                                'default' => 'standard',
                                'options' => [
                                    'standard' => 'Standard',
                                    'portrait' => 'Tall portrait',
                                    'wide' => 'Wide (full width)',
                                    'panoramic' => 'Panoramic banner (full width)',
                                ],
                            ],
                        ],
                        'default' => bruce_hamm_photo_rows([
                            ['image' => $a('images/gallery/bruce-baba-1-cropped.jpg'), 'alt' => 'Ali Akbar Khan, Bruce Hamm, and ensemble performing with tabla, sarod, and tanpura', 'shape' => 'panoramic'],
                            ['image' => $a('images/gallery/bruce-with-swapan-da.png'), 'alt' => 'Bruce Hamm seated with Swapan Chaudhuri'],
                            ['image' => $a('images/gallery/img-3440.jpg'), 'alt' => 'Bruce Hamm performing sarod with tabla at an evening concert'],
                            ['image' => $a('images/gallery/bruce-at-ali-akbar-college.jpg'), 'alt' => 'Bruce Hamm playing sarod at Ali Akbar College beneath a portrait of Ali Akbar Khan', 'shape' => 'portrait'],
                            ['image' => $a('images/gallery/sarod-class-80s.jpg'), 'alt' => 'Bruce Hamm playing sarod in class in the 1980s, with Ali Akbar Khan and fellow students', 'shape' => 'portrait'],
                            ['image' => $a('images/gallery/bruce-and-samir.jpg'), 'alt' => 'Bruce Hamm performing sarod with Samir on tabla at Chhandayan, Delhi'],
                            ['image' => $a('images/gallery/bruce-2016-calcutta.jpg'), 'alt' => 'Bruce Hamm on stage in Calcutta, 2016, holding a rose with his sarod'],
                            ['image' => $a('images/gallery/bruce-with-gregg-johnson.png'), 'alt' => 'Bruce Hamm playing sarod with Gregg Johnson on tabla'],
                            ['image' => $a('images/gallery/jabalpur.jpg'), 'alt' => 'Bruce Hamm performing sarod with a sitar accompanist in Jabalpur'],
                            ['image' => $a('images/gallery/alladuin-fes-aak.jpg'), 'alt' => 'Ali Akbar Khan, Swapan Chaudhuri, and ensemble at the 1st Annual Allaudin Khan Festival, 1991', 'shape' => 'wide'],
                        ]),
                    ],
                ],
            ],
        ],

        'music' => [
            $header_box('music'),
            $album_fields(
                1,
                'Music: first album',
                $a('images/morning-ragas-cover.png'),
                'Morning Ragas — Bruce Hamm, Sarod; Marcus Wise, Tabla',
                [
                    ['title' => 'Rag Jogia Kalengra – Tintal', 'meta' => 'Sarod · Recording', 'audio' => $a('audio/1-Audio-Track-6.mp3')],
                    ['title' => 'Rag Jogia Kalengra – Jhaptal', 'meta' => 'Sarod · Recording', 'audio' => $a('audio/2-Audio-Track-11.mp3')],
                    ['title' => 'Rag Bilaskhani Todi Alap Jor – Slow Tintal', 'meta' => 'Sarod · Recording', 'audio' => $a('audio/3-Audio-Track-7.mp3')],
                ]
            ),
            $album_fields(
                2,
                'Music: second album',
                $a('images/evening-ragas-cover.png'),
                'Evening Ragas — Bruce Hamm, Sarod; Ashis Paul, Tabla',
                [
                    ['title' => 'Rag Hem Bihag – Alap & Jor', 'meta' => '22.25', 'audio' => ''],
                    ['title' => 'Rag Hem Bihag – Gats in Tintal', 'meta' => '18.17', 'audio' => ''],
                    ['title' => 'Rag Bagkauns – Gat in Slow Tintal', 'meta' => '21.29', 'audio' => ''],
                    ['title' => 'Rag Gawati – Gat in Tintal', 'meta' => '10.26', 'audio' => ''],
                ]
            ),
            [
                'id' => 'music_other',
                'title' => 'Music: other tracks and labels',
                'fields' => [
                    ['key' => 'coming_soon_label', 'label' => 'Label above tracks that have no audio yet', 'type' => 'text', 'default' => 'Coming Soon'],
                    ['key' => 'other_tracks_title', 'label' => 'Heading for the other tracks', 'type' => 'text', 'default' => 'Other Tracks'],
                    [
                        'key' => 'other_tracks',
                        'label' => 'Other tracks',
                        'type' => 'repeater',
                        'item_label' => 'title',
                        'add_label' => 'Add track',
                        'fields' => [
                            ['key' => 'title', 'label' => 'Track title', 'type' => 'text', 'default' => ''],
                            ['key' => 'audio', 'label' => 'Audio file (MP3)', 'type' => 'audio', 'default' => ''],
                        ],
                        'default' => [
                            ['title' => 'Chandranadan – Alap', 'audio' => $a('audio/Chandranandan-Alap-Mono.mp3')],
                            ['title' => 'Chandranandan – Vilambit Gat w/ Marcus Wise, tabla', 'audio' => $a('audio/Chandranandan-Vilambit-Tintal_final_mono.mp3')],
                            ['title' => 'Hindol Hem – Vilambit Gat w/ Pandit Swapan Chaudhuri, tabla', 'audio' => $a('audio/Hindol-Hem-Vilambit.mp3')],
                            ['title' => 'Bhoop Mand – Chachar w/ Nikhil Pandya, tabla', 'audio' => $a('audio/BhoopMand_web_final.mp3')],
                            ['title' => 'Chandranandan – Fast Gat w/ Marcus Wise, tabla', 'audio' => $a('audio/Chandranandan-Fast-Gat.mp3')],
                        ],
                    ],
                ],
            ],
        ],

        'videos' => [
            $header_box('videos'),
            [
                'id' => 'videos_list',
                'title' => 'Videos',
                'intro' => 'Paste the address of each YouTube video (for example https://www.youtube.com/watch?v=abc123). To start part-way in, add &t=111 (seconds) to the address.',
                'fields' => [
                    [
                        'key' => 'videos',
                        'label' => 'Videos',
                        'type' => 'repeater',
                        'item_label' => 'title',
                        'add_label' => 'Add video',
                        'fields' => [
                            ['key' => 'url', 'label' => 'YouTube video address', 'type' => 'url', 'default' => ''],
                            ['key' => 'title', 'label' => 'Title (for screen readers)', 'type' => 'text', 'default' => ''],
                        ],
                        'default' => [
                            ['url' => 'https://www.youtube.com/watch?v=_OTR5QUcVR8&t=111s', 'title' => 'Bruce Hamm performance video'],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'videos_channel',
                'title' => 'YouTube channel box',
                'fields' => [
                    ['key' => 'channel_photo', 'label' => 'Photo', 'type' => 'image', 'default' => $a('images/gallery/bruce-portrait.png')],
                    ['key' => 'channel_photo_alt', 'label' => 'Photo description (for screen readers)', 'type' => 'text', 'role' => 'alt', 'default' => 'Bruce Hamm playing sarod in studio portrait'],
                    ['key' => 'channel_title', 'label' => 'Heading', 'type' => 'text', 'default' => 'YouTube Channel'],
                    ['key' => 'channel_text', 'label' => 'Text', 'type' => 'textarea', 'default' => 'Watch sarod performances and music from Bruce Hamm on his YouTube channel.'],
                    ['key' => 'channel_url', 'label' => 'Channel address', 'type' => 'url', 'default' => 'https://www.youtube.com/@oobssarode'],
                    ['key' => 'channel_button', 'label' => 'Button text', 'type' => 'text', 'default' => 'Bruce Hamm on YouTube'],
                ],
            ],
        ],

        'events' => [
            $header_box('events'),
            [
                'id' => 'events_text',
                'title' => 'Events page text',
                'intro' => 'Add and edit concerts under Events in the left-hand menu. Concerts dated today or later appear under Upcoming; earlier ones move to Past automatically.',
                'fields' => [
                    ['key' => 'upcoming_title', 'label' => 'Upcoming heading', 'type' => 'text', 'default' => 'Upcoming Events'],
                    ['key' => 'upcoming_empty', 'label' => 'Message when there are no upcoming concerts', 'type' => 'textarea', 'default' => 'Coming soon. New concert dates and appearances will be posted here.'],
                    ['key' => 'upcoming_booking', 'label' => 'Booking line (the word "get in touch" links to Contact)', 'type' => 'text', 'default' => 'For booking inquiries, please get in touch.'],
                    ['key' => 'past_title', 'label' => 'Past heading', 'type' => 'text', 'default' => 'Past Events'],
                    ['key' => 'past_empty', 'label' => 'Message when there are no past concerts', 'type' => 'text', 'default' => 'No past events listed yet.'],
                ],
            ],
        ],

        'lessons' => [
            $header_box('lessons'),
            [
                'id' => 'lessons_slides',
                'title' => 'Lessons photo slideshow',
                'intro' => 'The lesson text is written in the editor above. These photos rotate at the top of the page.',
                'fields' => [
                    [
                        'key' => 'lessons_slides',
                        'label' => 'Photos',
                        'type' => 'repeater',
                        'item_label' => 'alt',
                        'add_label' => 'Add photo',
                        'fields' => $slide_fields,
                        'default' => [
                            ['image' => $a('images/lessons/student-collage-2026.jpg'), 'alt' => "Collage of Bruce Hamm's students performing and studying sarod, sitar, and tabla"],
                            ['image' => $a('images/lessons/class-boys-2019.jpg'), 'alt' => 'Group music lesson with students playing sitar and tabla alongside Bruce Hamm and Joanna Mack'],
                            ['image' => $a('images/lessons/group-lesson.jpg'), 'alt' => 'Bruce Hamm with a young student beside a portrait of Ustad Ali Akbar Khan'],
                            ['image' => $a('images/lessons/teaching-vedika.jpg'), 'alt' => 'Bruce Hamm teaching a student sarod one on one'],
                        ],
                    ],
                    ['key' => 'lessons_button', 'label' => 'Button text (links to Contact)', 'type' => 'text', 'default' => 'Inquire About Lessons'],
                ],
            ],
        ],

        'contact' => [
            $header_box('contact'),
            [
                'id' => 'contact_info',
                'title' => 'Contact form',
                'intro' => 'Messages are sent through Formspree. The Formspree form ID is set under Appearance → Customize → Contact Form.',
                'fields' => [
                    ['key' => 'form_button', 'label' => 'Send button text', 'type' => 'text', 'default' => 'Send Message'],
                ],
            ],
        ],
    ];

    return $schema;
}
