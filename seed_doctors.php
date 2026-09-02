<?php
/**
 * One-time seeder: adds 10 sample doctors (with photos already placed
 * in Image/doctors/) across the hospital's departments.
 *
 * Visit this file once in your browser:
 *   http://localhost/hospital/seed_doctors.php
 *
 * It safely refuses to run a second time (checks for one of the seeded
 * emails first), so it's fine to leave in place, though deleting it
 * afterwards is good practice.
 */

require_once __DIR__ . '/config/db.php';

header('Content-Type: text/html; charset=utf-8');

function page($title, $bodyHtml, $ok = true) {
    $color = $ok ? '#1f4b43' : '#c62828';
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
    <title>{$title}</title>
    <style>
      body{font-family:Poppins,Segoe UI,Arial,sans-serif;background:#f4f6f5;margin:0;padding:60px 20px;color:#1c2a26;}
      .box{max-width:620px;margin:0 auto;background:#fff;border-radius:16px;padding:40px;box-shadow:0 10px 40px rgba(0,0,0,0.08);}
      h1{font-size:1.3rem;color:{$color};margin-top:0;}
      a.btn{display:inline-block;margin-top:10px;margin-right:10px;padding:10px 20px;background:#1f4b43;color:#fff;border-radius:30px;text-decoration:none;font-size:0.92rem;}
      code{background:#f0f2f0;padding:2px 6px;border-radius:4px;}
      ul{padding-left:20px;} li{margin-bottom:6px;}
    </style></head><body><div class='box'>{$bodyHtml}</div></body></html>";
    exit;
}

$doctors = [
    ['Dr. Emily Carter',   'MBBS, FCPS (Cardiology)',            'Cardiology',       'emily.carter@citycare.com',  '+1 (555) 201-1005', 10, 'Compassionate cardiologist focused on preventive heart care and long-term patient wellbeing.', 'doc_504c64692fa166de.jpg'],
    ['Dr. James Mitchel',  'MBBS, MD (Neurology)',                'Neurology',        'james.mitchel@citycare.com', '+1 (555) 201-1006', 11, 'Neurologist experienced in treating migraines, epilepsy, and other complex neurological conditions.', 'doc_10168a4eb24ffe60.jpg'],
    ['Dr. Sophia Ramirez', 'MBBS, DCH (Pediatrics)',              'Pediatrics',       'sophia.ramirez@citycare.com','+1 (555) 201-1007', 8,  'Warm and attentive pediatrician dedicated to the health and growth of children of all ages.', 'doc_17094ec017531dd9.jpg'],
    ['Dr. Daniel Wong',    'MBBS, MS (Orthopedics)',              'Orthopedics',      'daniel.wong@citycare.com',   '+1 (555) 201-1008', 13, 'Orthopedic specialist skilled in joint care, fracture management, and sports medicine.', 'doc_aa5d8552fa47bd7a.jpg'],
    ['Dr. Robert Hail',    'MBBS, FCPS (Dermatology)',            'Dermatology',      'robert.hail@citycare.com',   '+1 (555) 201-1009', 14, 'Dermatologist providing comprehensive skin, hair, and nail care for the whole family.', 'doc_6d36520ce504d510.jpg'],
    ['Dr. Marcus Webb',    'MBBS, FCPS (Internal Medicine)',      'General Medicine', 'marcus.webb@citycare.com',   '+1 (555) 201-1010', 16, 'General physician with broad experience diagnosing and managing everyday health concerns.', 'doc_d213c7ec7febc83f.jpg'],
    ['Dr. Lisa Chen',      'MBBS, FCPS (Ophthalmology)',          'Ophthalmology',    'lisa.chen@citycare.com',     '+1 (555) 201-1011', 6,  'Ophthalmologist offering complete eye care, from routine exams to advanced treatment.', 'doc_0d9ebbbf0a5b4199.jpg'],
    ['Dr. Arjun Patel',    'MBBS, MD (Pulmonology)',              'Pulmonology',      'arjun.patel@citycare.com',   '+1 (555) 201-1012', 10, 'Pulmonologist specializing in asthma, COPD, and other respiratory conditions.', 'doc_ea062c53a1e0581c.jpg'],
    ['Dr. William Turner', 'MBBS, FCPS (Gynecology & Obstetrics)','Gynecology',       'william.turner@citycare.com','+1 (555) 201-1013', 20, 'Senior gynecologist with two decades of experience in women\'s health and maternal care.', 'doc_1af2f0c9a1c9e27e.jpg'],
    ['Dr. Kevin Brooks',   'MBBS, MD (Cardiology)',               'Cardiology',       'kevin.brooks@citycare.com',  '+1 (555) 201-1014', 9,  'Cardiologist focused on early detection and management of cardiovascular disease.', 'doc_c8260e458959f262.jpg'],
];

// ---- Guard: don't re-run if already seeded ----
$check = $pdo->prepare('SELECT id FROM doctors WHERE email = ?');
$check->execute([$doctors[0][3]]);
if ($check->fetch()) {
    page('Already seeded', "<h1>✅ These doctors are already in the database</h1>
        <p>Nothing to do — the sample doctors have already been added.</p>
        <a class='btn' href='admin/doctors/list.php'>Go to Admin Panel</a>
        <a class='btn' href='doctors.php'>View Doctors Page</a>
        <p style='margin-top:20px;color:#888;font-size:0.85rem;'>You can safely delete <code>seed_doctors.php</code> now.</p>");
}

$deptStmt = $pdo->prepare('SELECT id FROM departments WHERE name = ?');
$insertStmt = $pdo->prepare(
    'INSERT INTO doctors (name, designation, department_id, email, phone, experience_years, bio, photo, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, "active")'
);

$inserted = 0;
$pdo->beginTransaction();
try {
    foreach ($doctors as $d) {
        [$name, $designation, $deptName, $email, $phone, $exp, $bio, $photo] = $d;

        $deptStmt->execute([$deptName]);
        $dept = $deptStmt->fetch();
        if (!$dept) {
            throw new Exception("Department not found: {$deptName}");
        }

        $insertStmt->execute([$name, $designation, $dept['id'], $email, $phone, $exp, $bio, $photo]);
        $inserted++;
    }
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    page('Seeding failed', "<h1>Something went wrong</h1><p>{$e->getMessage()}</p>
        <p>No doctors were added (the operation was rolled back). Please tell Claude this exact error message.</p>", false);
}

page('Doctors added', "<h1>✅ {$inserted} doctors added successfully</h1>
    <p>They're now live on the public Doctors page and visible in the admin panel.</p>
    <a class='btn' href='doctors.php'>View Doctors Page</a>
    <a class='btn' href='admin/doctors/list.php'>Go to Admin Panel</a>
    <p style='margin-top:20px;color:#888;font-size:0.85rem;'>For tidiness, you can delete <code>seed_doctors.php</code> now — it won't run again anyway.</p>");
