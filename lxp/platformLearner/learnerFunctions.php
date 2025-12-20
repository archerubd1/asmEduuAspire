<?php
/**
 * Astraal LXP - Learner Utility Functions
 * Modular helper for learning path and related pages
 * PHP 5.4 compatible | UwAmp / GoDaddy Safe
 */

// -----------------------------------------------------------------------------
// Map Course Classification / Learning Path Category
// -----------------------------------------------------------------------------
function mapClassification($classification) {
    // Normalized valid classifications for consistency
    $valid = array(
        'K-12',
        'Active Learning',
        'Curated Paths',
        'Skills Booster',
        'Level Up Courses',
        'Crowd Favourites'
    );

    $classification = ucwords(trim(strtolower($classification)));
    return in_array($classification, $valid) ? $classification : '';
}

// -----------------------------------------------------------------------------
// Render Courses Table by Category
// -----------------------------------------------------------------------------
function echoCoursesByCategory($rows, $category) {
    $found = false;

    $html = '
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="text-align:center;">Code</th>
            <th style="text-align:center;">Name</th>
            <th style="text-align:center;">Direction</th>
            <th colspan="2" style="text-align:center;">Action</th>
          </tr>
        </thead>
        <tbody>';

    foreach ($rows as $row) {
        $classification = isset($row['direction_name']) ? $row['direction_name'] : '';
        $mapped = mapClassification($classification);

        if ($mapped === $category) {
            $found = true;

            $html .= '
            <tr>
              <td class="text-center">' . htmlspecialchars($row['course_id']) . '</td>
              <td>' . htmlspecialchars($row['course_name']) . '</td>
              <td>' . htmlspecialchars($classification) . '</td>

              <td class="text-center">
                <a href="course-description.php?cid=' . urlencode($row['course_id']) . '" 
                   title="View Course Description">
                  <i class="bx bx-book-open text-success" style="font-size:22px;"></i>
                </a>
              </td>

              <td class="text-center">
                <a href="#" 
                   onclick="autoLoginAndRedirect(\'start_learning\', ' . (int)$row['course_id'] . ', ' . (int)$row['first_lesson_id'] . ')" 
                   title="Start Learning">
                  <i class="bx bx-play-circle text-primary" style="font-size:22px;"></i>
                </a>
              </td>
            </tr>';
        }
    }

    $html .= '</tbody></table></div>';

    echo $found ? $html : '<h6 class="text-muted">No courses available in this category yet.</h6>';
}
?>
