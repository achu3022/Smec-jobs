<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $tsv = <<<EOT
Automation	46100	Professional Diploma in Industrial Automation with AI	6 Month	90000	75000	70000	Tech Degree, ITI, Diploma, B-Tech Above	Yes
	46101	Certified Industrial Automation Engineer	3 Month	50000	45000	42000	Tech Degree, ITI, Diploma, B-Tech Above	Yes
	46102	Ship Maintenance Engineer Course 	12 Month	350000	300000	275000	Plus Two	
Instrumentation	46200	Diploma in Instrumentation & Control Systems	6 month	90000	75000	70000	Plus Two	Yes
	46201	Certified Instrumentation Engineer	3 Month	50000	45000	42000	Tech Degree, ITI, Diploma, B-Tech Above	Yes
Embedded System & VLSI	46300	Professional Diploma in Embedded Firmware	6 Month	75000	65000	60000	Diploma, Tech Degree (Electrical/ Electronics / CSE)	Yes
	46301	Certified VLSI Engineer	3 Month	50000	40000	37000	B-Tech Above	Yes
	46302	Industrial Robotics with AI	9 Month	175000	150000	140000	Diploma / B-Tech Above	Yes
	46303	Certified Advanced IoT Engineer	3 Month	60000	50000	47000	B-Tech Above	Yes
BMS	46400	Diploma in BMS	6 Month	90000	75000	70000	Any Plus Two	Yes
	46401	Certified BMS Engineer	3 Month	50000	45000	42000	Diploma, Degree & Above	Yes
	46402	Certified ELV Engineer	3 Month	45000	40000	37000	Diploma, Degree & Above	Yes
MEP	46500	Professional Diploma in MEP & BIM Engineer	6 Month	75000	65000	60000	Diploma, B-tech	Yes
Civil	46600	Professional Diploma in CPM & BIM Modeler	6 Month	90000	55000	52000	Diploma, B-tech	Yes
	46601	Diploma in BIM Modeler	5 Month	75000	45000	42000	ITI/Diploma/B-Tech	Yes
	46602	Diploma in Interior Designing	4 Month	50000	35000	33000	ITI/Diploma/B-Tech	Yes
	46603	Certified Quantity Surveyor & Quality Control Engineer	3 Month	45000	35000	33000	ITI/Diploma/B-Tech	Yes
Oil & Gas	46700	Professional Diploma in Oil and gas with Project Management	6 Month	75000	65000	60000	Diploma, B-tech	Yes
	46701	Diploma in Oil & Gas Technician	6 Month	75000	65000	60000	Plus Two	Yes
	46702	Certified Quality & Safety Engineer	3 Month	50000	35000	33000	Diploma, B-tech	Yes
	46703	Professional Diploma in Industrial Fire & Safety	6 Month	75000	65000	60000	Plus Two/Diploma/Degree/Btech	Yes
Fintech	46800	SAP Certified Application Associate (SAP S/4HANA Finance)	2 Month	45000	35000	33000	Any Degree	No
	46801	SAP Certified Application Associate (SAP S/4HANA MM)	2 Month	45000	35000	33000	Any Degree	No
	46802	Certified Indian & Foreign Accounting Professional	4 Month	45000	35000	33000	Any Degree	Yes
	46803	Diploma Indian & Foreign Accounting Professional	9 Month	120000	95000	90000	Plus Two	Yes
	46804	Professional Diploma in Corporate Account Management	6 Month	75000	65000	60000	Any Degree	Yes
	46805	Professional Diploma in Financial Analyst Management	6 Month	75000	65000	60000	Any Degree	Yes
Networking	46900	Professional Diploma in Cyber Security	9 Month	100000	75000	70000	Diploma, Any Degree	Yes
	46901	Professional Microsoft & Azure Cloud Architect	6 Month	75000	65000	60000	Diploma, Any Degree	Yes
	46902	Professional Linux & AWS Cloud Architect	6 Month	75000	65000	60000	Diploma, Any Degree	Yes
	46903	Certified Networking Engineer (Windows)	6 Month	60000	45000	42000	Plus Two,Diploma, Any Degree	Yes
	46904	Certified Networking Engineer (Linux)	6 Month	60000	45000	42000	Diploma, Any Degree	Yes
Graphics & Film Editing	47000	AI Integrated Graphic designing & Video Editing	7 Month	90000	75000	70000	Plus two and Above	Yes
	47001	Master Program in Film Editing & Post Production	12 Month	150000	125000	120000	Plus two and Above	Yes
Digital Marketing	47100	Professional Diploma in Digital Marketing with AI	6 Month	75000	65000	60000	Plus Two and Above	Yes
36	Software Development	56100	 Gen AI Integrated Python Fullstack 	6 Month	45000	45000	43000	Diploma, Any Degree	Yes
37	Software Development	56101	 Gen AI Integrated ME(A)RN Stack	6 Month	45000	45000	43000	Diploma, Any Degree	Yes
38	Software Development	56102	 Gen AI Integrated Flutter Development	4 Month	40000	40000	38000	Diploma, Any Degree	Yes
39	Software Development	56103	 Gen AI Integrated .Net Fullstack	6 Month	45000	45000	43000	Diploma, Any Degree	Yes
40	Software Development	56104	 Gen AI Integrated UI/UX Designing	4 Month	40000	40000	38000	Plus two and Above	Yes
41	Software Testing	56105	 Gen AI Integrated Software Testing	4 Month	35000	35000	33000	Diploma, Any Degree	Yes
42	Data Science	56106	Professional Certificate in Data Analytics with Prompt Engineering	5-6 Month	45000	45000	43000	Diploma, Any Degree	Yes
43	Data Science	56107	Professional Certificate in Advanced AI And ML with Gen-AI	7 Month	65000	65000	63000	Diploma, Any Degree	Yes
44	Data Science	56108	Master in Advanced AI And BigData Analytics With Gen-AI	9 Month	95000	95000	93000	Diploma, Any Degree	Yes
45	Logistics	66100	Profesional Diploma in SCM and Logistics	6 Month	65000	65000	58500	Plus Two and Above	Yes
46	Logistics	66101	Advanced Diploma in SCM and Logistics	9 Month	95000	95000	85500	Plus Two and Above	Yes
47	Logistics	66102	PG Diploma in AI SCM and Logistics	12 Month	125000	125000	112500	Plus Two and Above	Yes
48	Logistics	66103	Executive Certificate in SCM and Logistics (Online Only)	3 Month	45000	45000	40500	Plus Two and Above	Yes
49	Logistics	66104	Diploma in Marine Logistics	6 Month	70000	70000	63000	Plus Two and Above	Yes
50	HCM	66105	Diploma in Hospital Administration	6 Month	60000	60000	54000	Plus Two and Above	Yes
51	HCM	66106	Professional Diploma in Health Care and Hospitality Management	9 Month	90000	90000	81000	Plus Two and Above	Yes
52	HCM	66107	PG Diploma in Healthcare and Business Management	12 Month	120000	120000	108000	Plus Two and Above	Yes
53	HR	66108	Diploma in HR Management and Office Administration	5 Month	50000	50000	45000	Plus Two and Above	Yes
54	HR	66109	Profesional Diploma in HR Management and Industrial Relation	7 Month	75000	75000	67500	Plus Two and Above	Yes
55	HR	66110	PG Diploma in HR Management and Labour Laws	9 Month	95000	95000	85500	Plus Two and Above	Yes
EOT;

        $lines = explode("\n", trim($tsv));
        $currentCategory = null;

        foreach ($lines as $line) {
            $line = rtrim($line);
            if (empty($line)) continue;

            $cols = explode("\t", $line);
            
            // Handle lines starting with an ID (e.g. "45 Logistics 66100 ...")
            if (is_numeric($cols[0]) && isset($cols[1]) && !is_numeric($cols[1])) {
                // Drop the first numeric column
                array_shift($cols);
            }

            // Identify Category
            if (!empty($cols[0])) {
                $categoryName = trim($cols[0]);
                $currentCategory = CourseCategory::firstOrCreate(
                    ['slug' => Str::slug($categoryName)],
                    ['name' => $categoryName]
                );
            }

            // At this point:
            // $cols[0] is Category (or empty if same as previous)
            // $cols[1] is Course Code
            // $cols[2] is Title
            // $cols[3] is Duration
            // $cols[4] is Fee1
            // $cols[5] is Fee2
            // $cols[6] is Fee3
            // $cols[7] is Qualifications
            // $cols[8] is Placement (Yes/No)

            if (!isset($cols[1]) || empty($cols[1])) continue;

            $courseCode = trim($cols[1]);
            $title = isset($cols[2]) ? trim($cols[2]) : null;
            $duration = isset($cols[3]) ? trim($cols[3]) : null;
            $fee1 = isset($cols[4]) ? floatval(str_replace(',', '', trim($cols[4]))) : null;
            $fee2 = isset($cols[5]) ? floatval(str_replace(',', '', trim($cols[5]))) : null;
            $fee3 = isset($cols[6]) ? floatval(str_replace(',', '', trim($cols[6]))) : null;
            $qualifications = isset($cols[7]) ? trim($cols[7]) : null;
            $placement = isset($cols[8]) && strtolower(trim($cols[8])) === 'yes';

            if ($currentCategory && $title) {
                Course::updateOrCreate(
                    ['course_code' => $courseCode],
                    [
                        'course_category_id' => $currentCategory->id,
                        'title' => $title,
                        'duration' => $duration,
                        'fee_1' => $fee1,
                        'fee_2' => $fee2,
                        'fee_3' => $fee3,
                        'price' => $fee3, // Using Fee3 as the main price to display
                        'qualifications' => $qualifications,
                        'placement_assistance' => $placement,
                    ]
                );
            }
        }
        
        $this->command->info('Course database seeded successfully.');
    }
}
