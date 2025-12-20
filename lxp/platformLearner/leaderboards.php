<?php
/**
 *  Astraal LXP - Learner Leaderboards (6 Summary Cards + Comparisons)
 *  Static demo – PHP 5.4 compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified session management

$page = "gamification";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}
?>

<div class="layout-page">

  <?php require_once('learnersNav.php'); ?>

  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">

        <?php
        // SweetAlert success
        if (isset($_REQUEST['msg'])) {
            $successMessage = base64_decode(urldecode($_GET['msg']));
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        swal.fire("Successful!", "' . $successMessage . '", "success");
                        var urlWithoutMsg = window.location.origin + window.location.pathname;
                        history.replaceState({}, document.title, urlWithoutMsg);
                    });
                  </script>';
        }

        // SweetAlert error
        if (isset($_REQUEST['error'])) {
            $errorMessage = base64_decode(urldecode($_GET['error']));
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        swal.fire("Invalid Registration!!", "' . $errorMessage . '", "error");
                        var urlWithoutError = window.location.origin + window.location.pathname;
                        history.replaceState({}, document.title, urlWithoutError);
                    });
                  </script>';
        }
        ?>

        <div class="col-lg-12 mb-4 order-0">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="d-flex justify-content-end mb-3">
            <ol class="breadcrumb breadcrumb-style1">
              <li class="breadcrumb-item">
                <a href="#">Gamification</a>
              </li>
              <li class="breadcrumb-item active">Leaderboards</li>
            </ol>
          </nav>

          <!-- =============================== -->
          <!--   SIX PERSONAL SUMMARY CARDS    -->
          <!-- =============================== -->
          <div class="row g-3">

            <!-- 1. Your Rank -->
            <div class="col-md-4 col-xl-4">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">📜 Your Rank</h6>
                </div>
                <div class="card-body">
                  <div class="d-flex align-items-baseline mb-2">
                    <h2 class="mb-0" id="yourRankLabel">#–</h2>
                    <span class="ms-2" id="yourRankMedal"></span>
                  </div>
                  <p class="mb-3 text-muted small" id="yourRankDetail">
                    Calculating your position in the cohort…
                  </p>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#leaderboardModal"
                  >
                    View comparison
                  </button>
                </div>
              </div>
            </div>

            <!-- 2. Your Progress -->
            <div class="col-md-4 col-xl-4">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">📊 Your Progress</h6>
                </div>
                <div class="card-body">
                  <h2 class="mb-1" id="yourProgressPercent">0%</h2>
                  <p class="mb-2 text-muted small" id="yourProgressDetail">
                    Overall course completion snapshot.
                  </p>
                  <div class="progress mb-3" style="height: 6px;">
                    <div
                      id="yourProgressBar"
                      class="progress-bar"
                      role="progressbar"
                      style="width: 0%;"
                      aria-valuenow="0"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#progressModal"
                  >
                    View comparison
                  </button>
                </div>
              </div>
            </div>

            <!-- 3. Your Milestones -->
            <div class="col-md-4 col-xl-4">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">🏁 Your Milestones</h6>
                </div>
                <div class="card-body">
                  <h2 class="mb-1" id="yourMilestonesCount">0</h2>
                  <p class="mb-2 text-muted small" id="yourMilestonesDetail">
                    Key checkpoints completed in this learning path.
                  </p>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#milestonesModal"
                  >
                    View comparison
                  </button>
                </div>
              </div>
            </div>

            <!-- 4. Your Skills -->
            <div class="col-md-4 col-xl-4">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">🎯 Your Skills</h6>
                </div>
                <div class="card-body">
                  <h2 class="mb-1" id="yourSkillsCount">0</h2>
                  <p class="mb-2 text-muted small" id="yourSkillsDetail">
                    Skills validated through completed milestones.
                  </p>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#skillsModal"
                  >
                    View comparison
                  </button>
                </div>
              </div>
            </div>

            <!-- 5. Your Learning Hours -->
            <div class="col-md-4 col-xl-4">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">⏳ Your Learning Hours</h6>
                </div>
                <div class="card-body">
                  <h2 class="mb-1" id="yourHoursAvg">0 hrs</h2>
                  <p class="mb-1 text-muted small" id="yourHoursDetail">
                    Average weekly learning time (last 4 weeks).
                  </p>
                  <small class="text-muted" id="yourHoursPeak">
                    Peak week: – hrs
                  </small>
                  <div class="mt-3">
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#hoursModal"
                    >
                      View comparison
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- 6. Your Consistency -->
            <div class="col-md-4 col-xl-4">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">📈 Your Consistency</h6>
                </div>
                <div class="card-body">
                  <h2 class="mb-1" id="yourConsistencyLabel">–</h2>
                  <p class="mb-2 text-muted small" id="yourConsistencyDetail">
                    Stability of your weekly learning effort.
                  </p>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#consistencyModal"
                  >
                    View comparison
                  </button>
                </div>
              </div>
            </div>

          </div> <!-- /row (cards) -->

        </div> <!-- /col-lg-12 -->

      </div> <!-- /row -->
    </div> <!-- /container-xxl -->
    <!-- / Content -->

    <!-- ========================== -->
    <!--  MODALS: View Comparisons  -->
    <!-- ========================== -->

    <!-- 1. Leaderboard Modal -->
    <div class="modal fade" id="leaderboardModal" tabindex="-1" aria-labelledby="leaderboardModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="leaderboardModalLabel">Leaderboard – Full Ranking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="mb-3">
              Compare your rank and learning effort with other learners.
            </p>
            <div class="table-responsive">
              <table class="table table-striped table-hover text-center align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>Rank</th>
                    <th>Learner</th>
                    <th>Progress (%)</th>
                    <th>Milestones</th>
                    <th>Skills</th>
                    <th>Avg Weekly Hrs</th>
                    <th>Peak Hrs</th>
                  </tr>
                </thead>
                <tbody id="leaderboardModalTable">
                  <!-- Filled by JS -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Progress Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="progressModalLabel">Your Progress vs Cohort</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="progressModalChart"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Milestones Modal -->
    <div class="modal fade" id="milestonesModal" tabindex="-1" aria-labelledby="milestonesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="milestonesModalLabel">Your Milestones vs Cohort</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="milestonesModalChart"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. Skills Modal -->
    <div class="modal fade" id="skillsModal" tabindex="-1" aria-labelledby="skillsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="skillsModalLabel">Your Skill Profile vs Cohort</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="skillsModalChart"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 5. Hours Modal -->
    <div class="modal fade" id="hoursModal" tabindex="-1" aria-labelledby="hoursModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="hoursModalLabel">Your Weekly Learning Trend vs Cohort</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="hoursModalChart"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- 6. Consistency Modal -->
    <div class="modal fade" id="consistencyModal" tabindex="-1" aria-labelledby="consistencyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="consistencyModalLabel">Your Consistency vs Cohort</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="consistencyModalChart"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================== -->
    <!--  JAVASCRIPT (Static Data)  -->
    <!-- ========================== -->
    <script>
      // Static "current learner"
      var currentLearnerName = "You";

      // ==============================
      //  Static Learner Data
      // ==============================
      const learners = [
        { name: "Alice",   progress: 95, milestones: 8, skills: 6, hours: [12, 14, 15, 16] },
        { name: "Bob",     progress: 85, milestones: 7, skills: 5, hours: [10, 13, 12, 15] },
        { name: "Charlie", progress: 75, milestones: 6, skills: 4, hours: [9, 10, 11, 14] },
        { name: currentLearnerName, progress: 68, milestones: 5, skills: 4, hours: [8, 12, 13, 11] },
        { name: "David",   progress: 60, milestones: 4, skills: 3, hours: [7, 9, 10, 12] },
        { name: "Eve",     progress: 50, milestones: 3, skills: 2, hours: [5, 7, 8, 10] }
      ];

      // Sort learners by progress (Descending Order)
      learners.sort(function (a, b) { return b.progress - a.progress; });

      // =======================
      //  Helper functions
      // =======================
      function avgOf(arr) {
        if (!arr || arr.length === 0) return 0;
        var sum = arr.reduce(function (a, b) { return a + b; }, 0);
        return sum / arr.length;
      }

      function computeConsistency(hoursArray) {
        if (!hoursArray || hoursArray.length === 0) {
          return { label: "-", score: 0, range: 0 };
        }
        var maxH = Math.max.apply(null, hoursArray);
        var minH = Math.min.apply(null, hoursArray);
        var range = maxH - minH;
        var score = Math.max(0, 10 - range * 2); // smaller range → higher score (0–10)

        var label;
        if (range <= 2) label = "High";
        else if (range <= 5) label = "Medium";
        else label = "Low";

        return { label: label, score: score, range: range };
      }

      // Identify current learner & cohort
      var currentLearner = learners.find(function (l) { return l.name === currentLearnerName; });
      var others = learners.filter(function (l) { return l.name !== currentLearnerName; });
      var totalLearners = learners.length;
      var yourRankIndex = currentLearner ? learners.indexOf(currentLearner) : -1;
      var yourRank = yourRankIndex >= 0 ? (yourRankIndex + 1) : null;

      // Cohort averages
      var avgProgressOthers   = avgOf(others.map(function (l) { return l.progress; }));
      var avgMilestonesOthers = avgOf(others.map(function (l) { return l.milestones; }));
      var avgSkillsOthers     = avgOf(others.map(function (l) { return l.skills; }));
      var avgHoursOthersArr   = [0, 0, 0, 0];

      if (others.length > 0) {
        for (var w = 0; w < 4; w++) {
          avgHoursOthersArr[w] = avgOf(
            others.map(function (l) { return l.hours[w] || 0; })
          );
        }
      }

      // Consistency scores for learners
      learners.forEach(function (l) {
        var c = computeConsistency(l.hours);
        l.consistencyLabel = c.label;
        l.consistencyScore = c.score;
      });

      var avgConsistencyOthers = avgOf(others.map(function (l) { return l.consistencyScore; }));

      // =======================
      //  Fill summary cards
      // =======================
      if (currentLearner) {
        // 1. Rank card
        var rankLabel  = document.getElementById("yourRankLabel");
        var rankMedal  = document.getElementById("yourRankMedal");
        var rankDetail = document.getElementById("yourRankDetail");

        if (rankLabel) {
          rankLabel.textContent = "#" + yourRank;
        }
        if (rankMedal) {
          var medal = "";
          if (yourRank === 1) medal = "🥇";
          else if (yourRank === 2) medal = "🥈";
          else if (yourRank === 3) medal = "🥉";
          rankMedal.textContent = medal;
        }
        if (rankDetail) {
          rankDetail.textContent = "You are currently ranked #" + yourRank + " out of " + totalLearners + " active learners.";
        }

        // 2. Progress card
        var progressPercent = document.getElementById("yourProgressPercent");
        var progressDetail  = document.getElementById("yourProgressDetail");
        var progressBar     = document.getElementById("yourProgressBar");

        if (progressPercent) {
          progressPercent.textContent = currentLearner.progress + "%";
        }
        if (progressDetail) {
          progressDetail.textContent = "Overall progress across your current learning path.";
        }
        if (progressBar) {
          progressBar.style.width = currentLearner.progress + "%";
          progressBar.setAttribute("aria-valuenow", currentLearner.progress);
        }

        // 3. Milestones card
        var milestonesCount  = document.getElementById("yourMilestonesCount");
        var milestonesDetail = document.getElementById("yourMilestonesDetail");
        var maxMilestones    = 8; // static for demo

        if (milestonesCount) {
          milestonesCount.textContent = currentLearner.milestones;
        }
        if (milestonesDetail) {
          milestonesDetail.textContent = "You have completed " + currentLearner.milestones + " of " + maxMilestones + " key milestones.";
        }

        // 4. Skills card
        var skillsCount  = document.getElementById("yourSkillsCount");
        var skillsDetail = document.getElementById("yourSkillsDetail");

        if (skillsCount) {
          skillsCount.textContent = currentLearner.skills;
        }
        if (skillsDetail) {
          skillsDetail.textContent = "Skills mastered so far based on your completed milestones.";
        }

        // 5. Hours card
        var hoursAvgEl   = document.getElementById("yourHoursAvg");
        var hoursDetail  = document.getElementById("yourHoursDetail");
        var hoursPeakEl  = document.getElementById("yourHoursPeak");

        var avgHours = 0, peakHours = 0;
        if (currentLearner.hours && currentLearner.hours.length > 0) {
          var sumH = currentLearner.hours.reduce(function (a, b) { return a + b; }, 0);
          avgHours = (sumH / currentLearner.hours.length).toFixed(1);
          peakHours = Math.max.apply(null, currentLearner.hours);
        }

        if (hoursAvgEl) {
          hoursAvgEl.textContent = avgHours + " hrs";
        }
        if (hoursDetail) {
          hoursDetail.textContent = "Average weekly learning time over the last 4 weeks.";
        }
        if (hoursPeakEl) {
          hoursPeakEl.textContent = "Peak week: " + peakHours + " hrs";
        }

        // 6. Consistency card
        var cons = computeConsistency(currentLearner.hours);
        var consLabelEl   = document.getElementById("yourConsistencyLabel");
        var consDetailEl  = document.getElementById("yourConsistencyDetail");

        if (consLabelEl) {
          consLabelEl.textContent = cons.label;
        }
        if (consDetailEl) {
          consDetailEl.textContent = "Your weekly effort consistency is rated as " + cons.label + ".";
        }
      }

      // ==============================
      //  Leaderboard Modal Table
      // ==============================
      var modalTableHTML = "";
      learners.forEach(function (learner, index) {
        var rank = index + 1;
        var avgH = 0, peakH = 0;
        if (learner.hours && learner.hours.length > 0) {
          var s = learner.hours.reduce(function (a, b) { return a + b; }, 0);
          avgH = (s / learner.hours.length).toFixed(1);
          peakH = Math.max.apply(null, learner.hours);
        }
        var highlightRow = learner.name === currentLearnerName ? ' style="background-color:#fff3cd;"' : '';
        modalTableHTML += ''
          + '<tr' + highlightRow + '>'
          + '  <td>' + rank + '</td>'
          + '  <td>' + learner.name + '</td>'
          + '  <td>' + learner.progress + '%</td>'
          + '  <td>' + learner.milestones + '</td>'
          + '  <td>' + learner.skills + '</td>'
          + '  <td>' + avgH + '</td>'
          + '  <td>' + peakH + '</td>'
          + '</tr>';
      });
      var leaderboardModalTable = document.getElementById("leaderboardModalTable");
      if (leaderboardModalTable) {
        leaderboardModalTable.innerHTML = modalTableHTML;
      }

      // ======================================
      //  ApexCharts – Comparison in Modals
      // ======================================
      if (typeof ApexCharts !== "undefined" && currentLearner) {
        // 2. Progress Modal: You vs Cohort (Progress & Milestones & Skills)
        var optionsProgressModal = {
          series: [
            {
              name: currentLearnerName,
              data: [
                currentLearner.progress,
                currentLearner.milestones,
                currentLearner.skills
              ]
            },
            {
              name: 'Cohort Avg',
              data: [
                avgProgressOthers,
                avgMilestonesOthers,
                avgSkillsOthers
              ]
            }
          ],
          chart: { type: 'bar', height: 320 },
          xaxis: { categories: ['Progress (%)', 'Milestones', 'Skills'] },
          dataLabels: { enabled: true },
          legend: { position: 'top' }
        };
        new ApexCharts(document.querySelector("#progressModalChart"), optionsProgressModal).render();

        // 3. Milestones Modal: You vs Cohort (Milestones only)
        var optionsMilestonesModal = {
          series: [
            {
              name: currentLearnerName,
              data: [currentLearner.milestones]
            },
            {
              name: 'Cohort Avg',
              data: [avgMilestonesOthers]
            }
          ],
          chart: { type: 'bar', height: 320 },
          xaxis: { categories: ['Milestones Completed'] },
          dataLabels: { enabled: true },
          legend: { position: 'top' }
        };
        new ApexCharts(document.querySelector("#milestonesModalChart"), optionsMilestonesModal).render();

        // 4. Skills Modal: Radar – You vs Cohort (Skills, Milestones, Progress)
        var optionsSkillsModal = {
          series: [
            {
              name: currentLearnerName,
              data: [
                currentLearner.skills * 15,
                currentLearner.milestones * 12,
                currentLearner.progress * 1.2
              ]
            },
            {
              name: 'Cohort Avg',
              data: [
                avgSkillsOthers * 15,
                avgMilestonesOthers * 12,
                avgProgressOthers * 1.2
              ]
            }
          ],
          chart: { type: 'radar', height: 320 },
          labels: ['Skills Mastery', 'Milestones', 'Overall Progress'],
          legend: { position: 'top' }
        };
        new ApexCharts(document.querySelector("#skillsModalChart"), optionsSkillsModal).render();

        // 5. Hours Modal: Line – You vs Cohort
        var optionsHoursModal = {
          series: [
            {
              name: currentLearnerName,
              data: currentLearner.hours
            },
            {
              name: 'Cohort Avg',
              data: avgHoursOthersArr
            }
          ],
          chart: { type: 'line', height: 320 },
          xaxis: { categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'] },
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth' },
          legend: { position: 'top' }
        };
        new ApexCharts(document.querySelector("#hoursModalChart"), optionsHoursModal).render();

        // 6. Consistency Modal: Bar – You vs Cohort
        var optionsConsistencyModal = {
          series: [
            {
              name: currentLearnerName,
              data: [currentLearner.consistencyScore]
            },
            {
              name: 'Cohort Avg',
              data: [avgConsistencyOthers]
            }
          ],
          chart: { type: 'bar', height: 320 },
          xaxis: { categories: ['Consistency Score (0–10, higher is better)'] },
          dataLabels: { enabled: true },
          legend: { position: 'top' }
        };
        new ApexCharts(document.querySelector("#consistencyModalChart"), optionsConsistencyModal).render();
      }
    </script>

    <?php require_once('../platformFooter.php'); ?>

  </div> <!-- /content-wrapper -->
</div> <!-- /layout-page -->
