<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QuizDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@japura.lk'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'university' => 'University of Sri Jayewardenepura',
                'email_verified_at' => now(),
                'total_points' => 0,
                'level' => 1,
                'current_streak' => 0,
                'longest_streak' => 0,
            ]
        );

        // Sample students
        $universities = [
            'University of Sri Jayewardenepura',
            'University of Colombo',
            'University of Peradeniya',
            'University of Kelaniya',
            'University of Moratuwa',
        ];

        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => "student{$i}@japura.lk"],
                [
                    'name' => "Student {$i}",
                    'password' => Hash::make('password'),
                    'university' => $universities[$i - 1],
                    'email_verified_at' => now(),
                    'total_points' => rand(50, 500),
                    'level' => rand(1, 5),
                    'current_streak' => rand(0, 10),
                    'longest_streak' => rand(0, 20),
                ]
            );
        }

        // Categories
        $categoriesData = [
            ['name' => 'General Knowledge', 'slug' => 'general-knowledge', 'icon' => '🌍', 'color' => '#6366f1', 'description' => 'Test your general knowledge', 'sort_order' => 1],
            ['name' => 'Science & Technology', 'slug' => 'science-technology', 'icon' => '🔬', 'color' => '#10b981', 'description' => 'Explore science and technology', 'sort_order' => 2],
            ['name' => 'Sri Lankan History', 'slug' => 'sri-lankan-history', 'icon' => '🏛️', 'color' => '#f59e0b', 'description' => 'Learn about Sri Lankan history', 'sort_order' => 3],
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'icon' => '🔢', 'color' => '#3b82f6', 'description' => 'Mathematical challenges', 'sort_order' => 4],
            ['name' => 'English Language', 'slug' => 'english-language', 'icon' => '📝', 'color' => '#8b5cf6', 'description' => 'Improve your English skills', 'sort_order' => 5],
        ];

        foreach ($categoriesData as $catData) {
            $category = Category::firstOrCreate(['slug' => $catData['slug']], array_merge($catData, ['is_active' => true]));
            $this->createQuizzesForCategory($category);
        }
    }

    private function createQuizzesForCategory(Category $category): void
    {
        $quizzesData = $this->getQuizzesData($category->slug);
        foreach ($quizzesData as $quizData) {
            $slug = Str::slug($quizData['title']);
            $quiz = Quiz::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'title' => $quizData['title'],
                    'slug' => $slug,
                    'description' => $quizData['description'],
                    'difficulty' => $quizData['difficulty'],
                    'time_limit_per_question' => 30,
                    'points_per_correct' => 10,
                    'bonus_points_for_speed' => true,
                    'is_published' => true,
                    'total_questions' => 0,
                ]
            );

            if ($quiz->questions()->count() === 0) {
                foreach ($quizData['questions'] as $sortOrder => $qData) {
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $qData['text'],
                        'explanation' => $qData['explanation'] ?? null,
                        'sort_order' => $sortOrder,
                    ]);

                    foreach ($qData['options'] as $optOrder => $optData) {
                        Option::create([
                            'question_id' => $question->id,
                            'option_text' => $optData['text'],
                            'is_correct' => $optData['correct'],
                            'sort_order' => $optOrder,
                        ]);
                    }
                }

                $quiz->update(['total_questions' => $quiz->questions()->count()]);
            }
        }
    }

    private function getQuizzesData(string $categorySlug): array
    {
        $data = [
            'general-knowledge' => [
                [
                    'title' => 'World Capitals',
                    'description' => 'Test your knowledge of world capitals',
                    'difficulty' => 'easy',
                    'questions' => [
                        ['text' => 'What is the capital of France?', 'explanation' => 'Paris is the capital and most populous city of France.', 'options' => [['text' => 'London', 'correct' => false], ['text' => 'Paris', 'correct' => true], ['text' => 'Berlin', 'correct' => false], ['text' => 'Madrid', 'correct' => false]]],
                        ['text' => 'What is the capital of Australia?', 'explanation' => 'Canberra is the capital city of Australia.', 'options' => [['text' => 'Sydney', 'correct' => false], ['text' => 'Melbourne', 'correct' => false], ['text' => 'Canberra', 'correct' => true], ['text' => 'Brisbane', 'correct' => false]]],
                        ['text' => 'What is the capital of Japan?', 'explanation' => 'Tokyo is the capital of Japan.', 'options' => [['text' => 'Osaka', 'correct' => false], ['text' => 'Kyoto', 'correct' => false], ['text' => 'Tokyo', 'correct' => true], ['text' => 'Hiroshima', 'correct' => false]]],
                        ['text' => 'What is the capital of Brazil?', 'explanation' => 'Brasília is the capital of Brazil, not Rio de Janeiro or São Paulo.', 'options' => [['text' => 'Rio de Janeiro', 'correct' => false], ['text' => 'São Paulo', 'correct' => false], ['text' => 'Brasília', 'correct' => true], ['text' => 'Salvador', 'correct' => false]]],
                        ['text' => 'What is the capital of Canada?', 'explanation' => 'Ottawa is the capital of Canada.', 'options' => [['text' => 'Toronto', 'correct' => false], ['text' => 'Vancouver', 'correct' => false], ['text' => 'Montreal', 'correct' => false], ['text' => 'Ottawa', 'correct' => true]]],
                    ],
                ],
                [
                    'title' => 'Famous Inventions',
                    'description' => 'Who invented what?',
                    'difficulty' => 'medium',
                    'questions' => [
                        ['text' => 'Who invented the telephone?', 'explanation' => 'Alexander Graham Bell is credited with inventing the telephone in 1876.', 'options' => [['text' => 'Thomas Edison', 'correct' => false], ['text' => 'Alexander Graham Bell', 'correct' => true], ['text' => 'Nikola Tesla', 'correct' => false], ['text' => 'Guglielmo Marconi', 'correct' => false]]],
                        ['text' => 'Who invented the World Wide Web?', 'explanation' => 'Tim Berners-Lee invented the World Wide Web in 1989.', 'options' => [['text' => 'Bill Gates', 'correct' => false], ['text' => 'Steve Jobs', 'correct' => false], ['text' => 'Tim Berners-Lee', 'correct' => true], ['text' => 'Vint Cerf', 'correct' => false]]],
                        ['text' => 'In what year was the first iPhone released?', 'explanation' => 'The first iPhone was released by Apple on June 29, 2007.', 'options' => [['text' => '2005', 'correct' => false], ['text' => '2006', 'correct' => false], ['text' => '2007', 'correct' => true], ['text' => '2008', 'correct' => false]]],
                        ['text' => 'What does CPU stand for?', 'explanation' => 'CPU stands for Central Processing Unit.', 'options' => [['text' => 'Central Program Unit', 'correct' => false], ['text' => 'Computer Processing Unit', 'correct' => false], ['text' => 'Central Processing Unit', 'correct' => true], ['text' => 'Core Processing Unit', 'correct' => false]]],
                        ['text' => 'Who developed the theory of relativity?', 'explanation' => 'Albert Einstein developed the theory of relativity.', 'options' => [['text' => 'Isaac Newton', 'correct' => false], ['text' => 'Albert Einstein', 'correct' => true], ['text' => 'Stephen Hawking', 'correct' => false], ['text' => 'Max Planck', 'correct' => false]]],
                    ],
                ],
            ],
            'science-technology' => [
                [
                    'title' => 'Basic Physics',
                    'description' => 'Test your physics knowledge',
                    'difficulty' => 'medium',
                    'questions' => [
                        ['text' => 'What is the speed of light in vacuum?', 'explanation' => 'The speed of light in vacuum is approximately 3 × 10^8 m/s (299,792,458 m/s).', 'options' => [['text' => '3 × 10^6 m/s', 'correct' => false], ['text' => '3 × 10^8 m/s', 'correct' => true], ['text' => '3 × 10^10 m/s', 'correct' => false], ['text' => '3 × 10^4 m/s', 'correct' => false]]],
                        ['text' => 'What is Newton\'s first law of motion?', 'explanation' => 'Newton\'s first law states that an object at rest stays at rest and an object in motion stays in motion unless acted upon by an external force.', 'options' => [['text' => 'F = ma', 'correct' => false], ['text' => 'An object remains in its state unless acted upon by a force', 'correct' => true], ['text' => 'Every action has an equal and opposite reaction', 'correct' => false], ['text' => 'Energy cannot be created or destroyed', 'correct' => false]]],
                        ['text' => 'What is the chemical symbol for water?', 'explanation' => 'Water is composed of two hydrogen atoms and one oxygen atom, giving it the chemical formula H₂O.', 'options' => [['text' => 'HO', 'correct' => false], ['text' => 'H2O', 'correct' => true], ['text' => 'OH2', 'correct' => false], ['text' => 'HO2', 'correct' => false]]],
                        ['text' => 'What is the atomic number of Carbon?', 'explanation' => 'Carbon has an atomic number of 6, meaning it has 6 protons in its nucleus.', 'options' => [['text' => '4', 'correct' => false], ['text' => '6', 'correct' => true], ['text' => '8', 'correct' => false], ['text' => '12', 'correct' => false]]],
                        ['text' => 'Which planet is known as the Red Planet?', 'explanation' => 'Mars is called the Red Planet because of iron oxide (rust) on its surface.', 'options' => [['text' => 'Venus', 'correct' => false], ['text' => 'Jupiter', 'correct' => false], ['text' => 'Mars', 'correct' => true], ['text' => 'Saturn', 'correct' => false]]],
                    ],
                ],
                [
                    'title' => 'Computer Science Basics',
                    'description' => 'Fundamentals of computer science',
                    'difficulty' => 'medium',
                    'questions' => [
                        ['text' => 'What is an algorithm?', 'explanation' => 'An algorithm is a step-by-step procedure for solving a problem or accomplishing a task.', 'options' => [['text' => 'A programming language', 'correct' => false], ['text' => 'A step-by-step procedure for solving a problem', 'correct' => true], ['text' => 'A type of computer hardware', 'correct' => false], ['text' => 'A database management system', 'correct' => false]]],
                        ['text' => 'What does HTML stand for?', 'explanation' => 'HTML stands for HyperText Markup Language.', 'options' => [['text' => 'Hyperlink Text Markup Language', 'correct' => false], ['text' => 'HyperText Markup Language', 'correct' => true], ['text' => 'High-level Text Management Language', 'correct' => false], ['text' => 'HyperText Management Language', 'correct' => false]]],
                        ['text' => 'What is the binary representation of the decimal number 10?', 'explanation' => '10 in decimal = 1×8 + 0×4 + 1×2 + 0×1 = 1010 in binary.', 'options' => [['text' => '1001', 'correct' => false], ['text' => '1010', 'correct' => true], ['text' => '1100', 'correct' => false], ['text' => '0110', 'correct' => false]]],
                        ['text' => 'What does RAM stand for?', 'explanation' => 'RAM stands for Random Access Memory.', 'options' => [['text' => 'Read Access Memory', 'correct' => false], ['text' => 'Random Access Memory', 'correct' => true], ['text' => 'Rapid Access Module', 'correct' => false], ['text' => 'Read And Modify', 'correct' => false]]],
                        ['text' => 'Which of these is an object-oriented programming language?', 'explanation' => 'Java is a widely-used object-oriented programming language.', 'options' => [['text' => 'HTML', 'correct' => false], ['text' => 'CSS', 'correct' => false], ['text' => 'Java', 'correct' => true], ['text' => 'SQL', 'correct' => false]]],
                    ],
                ],
            ],
            'sri-lankan-history' => [
                [
                    'title' => 'Ancient Sri Lanka',
                    'description' => 'History of ancient Sri Lanka',
                    'difficulty' => 'medium',
                    'questions' => [
                        ['text' => 'What was the ancient name of Sri Lanka?', 'explanation' => 'Sri Lanka was historically known as Ceylon, and even earlier as Taprobane to the Greeks.', 'options' => [['text' => 'Serendip', 'correct' => false], ['text' => 'Ceylon', 'correct' => false], ['text' => 'Lanka', 'correct' => false], ['text' => 'All of the above', 'correct' => true]]],
                        ['text' => 'Who was the first king of the Anuradhapura Kingdom?', 'explanation' => 'Pandukabhaya founded the Anuradhapura Kingdom in 437 BC.', 'options' => [['text' => 'Vijaya', 'correct' => false], ['text' => 'Pandukabhaya', 'correct' => true], ['text' => 'Dutugamunu', 'correct' => false], ['text' => 'Devanampiyatissa', 'correct' => false]]],
                        ['text' => 'In which year did Buddhism arrive in Sri Lanka?', 'explanation' => 'Buddhism was introduced to Sri Lanka in 247 BC by Mahinda, son of Emperor Ashoka.', 'options' => [['text' => '300 BC', 'correct' => false], ['text' => '247 BC', 'correct' => true], ['text' => '200 BC', 'correct' => false], ['text' => '150 BC', 'correct' => false]]],
                        ['text' => 'Who built the famous Sigiriya Rock Fortress?', 'explanation' => 'King Kashyapa I built the Sigiriya Rock Fortress in the 5th century AD.', 'options' => [['text' => 'King Parakramabahu', 'correct' => false], ['text' => 'King Kashyapa I', 'correct' => true], ['text' => 'King Dutugamunu', 'correct' => false], ['text' => 'King Vijaya', 'correct' => false]]],
                        ['text' => 'What is the longest river in Sri Lanka?', 'explanation' => 'The Mahaweli River is the longest river in Sri Lanka at 335 km.', 'options' => [['text' => 'Kelani River', 'correct' => false], ['text' => 'Kalu River', 'correct' => false], ['text' => 'Mahaweli River', 'correct' => true], ['text' => 'Walawe River', 'correct' => false]]],
                        ['text' => 'When did Sri Lanka gain independence?', 'explanation' => 'Sri Lanka (then Ceylon) gained independence from Britain on February 4, 1948.', 'options' => [['text' => 'February 4, 1947', 'correct' => false], ['text' => 'February 4, 1948', 'correct' => true], ['text' => 'August 15, 1947', 'correct' => false], ['text' => 'January 26, 1950', 'correct' => false]]],
                    ],
                ],
                [
                    'title' => 'Modern Sri Lanka',
                    'description' => 'Test your knowledge of modern Sri Lankan history',
                    'difficulty' => 'hard',
                    'questions' => [
                        ['text' => 'Who was the first Prime Minister of Ceylon?', 'explanation' => 'D. S. Senanayake was the first Prime Minister of Ceylon after independence in 1948.', 'options' => [['text' => 'S. W. R. D. Bandaranaike', 'correct' => false], ['text' => 'D. S. Senanayake', 'correct' => true], ['text' => 'Dudley Senanayake', 'correct' => false], ['text' => 'Sir John Kotelawala', 'correct' => false]]],
                        ['text' => 'In what year did Ceylon become the Republic of Sri Lanka?', 'explanation' => 'Ceylon became the Republic of Sri Lanka on May 22, 1972.', 'options' => [['text' => '1970', 'correct' => false], ['text' => '1971', 'correct' => false], ['text' => '1972', 'correct' => true], ['text' => '1978', 'correct' => false]]],
                        ['text' => 'What is the currency of Sri Lanka?', 'explanation' => 'The Sri Lankan Rupee (LKR) is the official currency of Sri Lanka.', 'options' => [['text' => 'Sri Lankan Dollar', 'correct' => false], ['text' => 'Sri Lankan Rupee', 'correct' => true], ['text' => 'Lankan Peso', 'correct' => false], ['text' => 'Ceylon Pound', 'correct' => false]]],
                        ['text' => 'What is the capital city of Sri Lanka?', 'explanation' => 'Sri Jayawardenepura Kotte is the legislative capital of Sri Lanka, while Colombo serves as the commercial capital.', 'options' => [['text' => 'Colombo', 'correct' => false], ['text' => 'Kandy', 'correct' => false], ['text' => 'Sri Jayawardenepura Kotte', 'correct' => true], ['text' => 'Galle', 'correct' => false]]],
                        ['text' => 'What is the national flower of Sri Lanka?', 'explanation' => 'The Blue Lotus (Nil Mahanel) is the national flower of Sri Lanka.', 'options' => [['text' => 'Rose', 'correct' => false], ['text' => 'Jasmine', 'correct' => false], ['text' => 'Blue Lotus (Nil Mahanel)', 'correct' => true], ['text' => 'Orchid', 'correct' => false]]],
                    ],
                ],
            ],
            'mathematics' => [
                [
                    'title' => 'Basic Arithmetic',
                    'description' => 'Test your arithmetic skills',
                    'difficulty' => 'easy',
                    'questions' => [
                        ['text' => 'What is 15 × 13?', 'explanation' => '15 × 13 = 15 × 10 + 15 × 3 = 150 + 45 = 195', 'options' => [['text' => '185', 'correct' => false], ['text' => '190', 'correct' => false], ['text' => '195', 'correct' => true], ['text' => '200', 'correct' => false]]],
                        ['text' => 'What is the square root of 144?', 'explanation' => '√144 = 12 because 12 × 12 = 144', 'options' => [['text' => '10', 'correct' => false], ['text' => '11', 'correct' => false], ['text' => '12', 'correct' => true], ['text' => '13', 'correct' => false]]],
                        ['text' => 'What is 25% of 200?', 'explanation' => '25% of 200 = 0.25 × 200 = 50', 'options' => [['text' => '25', 'correct' => false], ['text' => '50', 'correct' => true], ['text' => '75', 'correct' => false], ['text' => '100', 'correct' => false]]],
                        ['text' => 'If a = 5 and b = 3, what is a² + b²?', 'explanation' => 'a² + b² = 25 + 9 = 34', 'options' => [['text' => '30', 'correct' => false], ['text' => '32', 'correct' => false], ['text' => '34', 'correct' => true], ['text' => '36', 'correct' => false]]],
                        ['text' => 'What is the LCM of 4 and 6?', 'explanation' => 'The Least Common Multiple of 4 and 6 is 12.', 'options' => [['text' => '8', 'correct' => false], ['text' => '12', 'correct' => true], ['text' => '16', 'correct' => false], ['text' => '24', 'correct' => false]]],
                    ],
                ],
                [
                    'title' => 'Algebra & Geometry',
                    'description' => 'Challenge your algebra and geometry skills',
                    'difficulty' => 'hard',
                    'questions' => [
                        ['text' => 'What is the area of a circle with radius 7 cm? (π ≈ 22/7)', 'explanation' => 'Area = πr² = (22/7) × 7² = 22 × 7 = 154 cm²', 'options' => [['text' => '44 cm²', 'correct' => false], ['text' => '154 cm²', 'correct' => true], ['text' => '176 cm²', 'correct' => false], ['text' => '308 cm²', 'correct' => false]]],
                        ['text' => 'Solve for x: 2x + 6 = 16', 'explanation' => '2x = 16 - 6 = 10, so x = 5', 'options' => [['text' => 'x = 4', 'correct' => false], ['text' => 'x = 5', 'correct' => true], ['text' => 'x = 6', 'correct' => false], ['text' => 'x = 7', 'correct' => false]]],
                        ['text' => 'In a right triangle, if one leg is 3 and the other is 4, what is the hypotenuse?', 'explanation' => 'Using the Pythagorean theorem: c² = 3² + 4² = 9 + 16 = 25, so c = 5.', 'options' => [['text' => '4', 'correct' => false], ['text' => '5', 'correct' => true], ['text' => '6', 'correct' => false], ['text' => '7', 'correct' => false]]],
                        ['text' => 'What is the sum of angles in a triangle?', 'explanation' => 'The sum of all interior angles of a triangle is always 180 degrees.', 'options' => [['text' => '90°', 'correct' => false], ['text' => '180°', 'correct' => true], ['text' => '270°', 'correct' => false], ['text' => '360°', 'correct' => false]]],
                        ['text' => 'What is the value of (3x - 2)² when x = 2?', 'explanation' => 'When x = 2: (3×2 - 2)² = (6-2)² = 4² = 16', 'options' => [['text' => '8', 'correct' => false], ['text' => '12', 'correct' => false], ['text' => '16', 'correct' => true], ['text' => '20', 'correct' => false]]],
                    ],
                ],
            ],
            'english-language' => [
                [
                    'title' => 'Grammar Basics',
                    'description' => 'Test your English grammar knowledge',
                    'difficulty' => 'easy',
                    'questions' => [
                        ['text' => 'Which of the following is a correct sentence?', 'explanation' => '"She doesn\'t know the answer" is correct. "She don\'t know" is incorrect; it should be "doesn\'t" for singular subjects.', 'options' => [['text' => 'She don\'t know the answer', 'correct' => false], ['text' => 'She doesn\'t know the answer', 'correct' => true], ['text' => 'She not know the answer', 'correct' => false], ['text' => 'She not knowing the answer', 'correct' => false]]],
                        ['text' => 'What is the plural of "child"?', 'explanation' => 'The plural of "child" is "children" (irregular plural).', 'options' => [['text' => 'childs', 'correct' => false], ['text' => 'childes', 'correct' => false], ['text' => 'children', 'correct' => true], ['text' => 'childrens', 'correct' => false]]],
                        ['text' => 'Choose the correct word: "I _____ to school every day."', 'explanation' => '"go" is the correct present simple form for first person singular (I).', 'options' => [['text' => 'goes', 'correct' => false], ['text' => 'go', 'correct' => true], ['text' => 'going', 'correct' => false], ['text' => 'gone', 'correct' => false]]],
                        ['text' => 'What type of word is "quickly"?', 'explanation' => '"Quickly" is an adverb that modifies verbs, adjectives, or other adverbs.', 'options' => [['text' => 'Adjective', 'correct' => false], ['text' => 'Noun', 'correct' => false], ['text' => 'Adverb', 'correct' => true], ['text' => 'Verb', 'correct' => false]]],
                        ['text' => 'Which sentence uses the correct form of "there/their/they\'re"?', 'explanation' => '"Their books are on the table" - "their" shows possession.', 'options' => [['text' => 'There books are on the table', 'correct' => false], ['text' => 'Their books are on the table', 'correct' => true], ['text' => 'They\'re books are on the table', 'correct' => false], ['text' => 'Ther books are on the table', 'correct' => false]]],
                    ],
                ],
                [
                    'title' => 'Vocabulary Builder',
                    'description' => 'Expand your English vocabulary',
                    'difficulty' => 'medium',
                    'questions' => [
                        ['text' => 'What is the meaning of "benevolent"?', 'explanation' => 'Benevolent means well-meaning and kindly; characterized by doing good.', 'options' => [['text' => 'Angry and hostile', 'correct' => false], ['text' => 'Well-meaning and kind', 'correct' => true], ['text' => 'Confused and lost', 'correct' => false], ['text' => 'Proud and arrogant', 'correct' => false]]],
                        ['text' => 'What is the antonym of "ancient"?', 'explanation' => '"Modern" is the antonym (opposite) of "ancient".', 'options' => [['text' => 'Old', 'correct' => false], ['text' => 'Historical', 'correct' => false], ['text' => 'Modern', 'correct' => true], ['text' => 'Traditional', 'correct' => false]]],
                        ['text' => 'Which word means "to make worse"?', 'explanation' => '"Aggravate" means to make a problem, injury, or offense worse or more serious.', 'options' => [['text' => 'Ameliorate', 'correct' => false], ['text' => 'Aggravate', 'correct' => true], ['text' => 'Alleviate', 'correct' => false], ['text' => 'Mitigate', 'correct' => false]]],
                        ['text' => 'What does "ubiquitous" mean?', 'explanation' => 'Ubiquitous means present, appearing, or found everywhere.', 'options' => [['text' => 'Very rare', 'correct' => false], ['text' => 'Present everywhere', 'correct' => true], ['text' => 'Extremely large', 'correct' => false], ['text' => 'Very old', 'correct' => false]]],
                        ['text' => 'Choose the correct synonym for "diligent"?', 'explanation' => '"Hardworking" is the best synonym for "diligent" which means having or showing care and conscientiousness in one\'s work.', 'options' => [['text' => 'Lazy', 'correct' => false], ['text' => 'Hardworking', 'correct' => true], ['text' => 'Careless', 'correct' => false], ['text' => 'Talented', 'correct' => false]]],
                    ],
                ],
            ],
        ];

        return $data[$categorySlug] ?? [];
    }
}
