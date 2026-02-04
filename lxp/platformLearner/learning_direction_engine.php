<?php
/**
 * Determine Learning Direction
 * Robust, explainable, milestone-aligned
 * With Trace Map for audit & UI explainability
 * PHP 5.x compatible
 */

function determineLearningDirection($intent, $skill_gap) {

    $reasons   = array();
    $rules_hit = array();

    /* ------------------------------
     * NORMALIZE INPUTS
     * ------------------------------ */

    $focus_axis  = isset($intent['reference_axis']) ? $intent['reference_axis'] : '';
    $stage       = isset($intent['context_stage']) ? $intent['context_stage'] : '';
    $motivation  = isset($intent['motivation_reason']) ? $intent['motivation_reason'] : '';
    $preference  = isset($intent['cognitive_preference']) ? $intent['cognitive_preference'] : '';
    $direction_i = isset($intent['intent_direction']) ? $intent['intent_direction'] : '';
    $constraints = isset($intent['constraints']) ? $intent['constraints'] : '';

    $exposure    = isset($skill_gap['exposure']) ? $skill_gap['exposure'] : '';
    $clarity     = isset($skill_gap['conceptual_clarity']) ? $skill_gap['conceptual_clarity'] : '';
    $application = isset($skill_gap['application_ability']) ? $skill_gap['application_ability'] : '';
    $tools       = isset($skill_gap['tool_readiness']) ? $skill_gap['tool_readiness'] : '';
    $confidence  = isset($skill_gap['confidence_level']) ? $skill_gap['confidence_level'] : '';
    $friction    = isset($skill_gap['learning_friction']) ? $skill_gap['learning_friction'] : '';

    /* ------------------------------
     * PRIORITY 0 — EXPLORATION
     * ------------------------------ */
    if ($focus_axis === 'exploration' || $direction_i === 'exploration') {

        $reasons[]   = 'Learner has not committed to a specific focus yet';
        $reasons[]   = 'Exploration allows low-pressure discovery and intent refinement';
        $rules_hit[] = 'P0_EXPLORATION';

        return array(
            'direction_code' => 'exploration',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * PRIORITY 1 — FOUNDATION BUILDING
     * ------------------------------ */
    if (
        $confidence === 'low' ||
        $clarity === 'unclear' ||
        in_array($tools, array('unfamiliar','setup_issues'))
    ) {

        $reasons[]   = 'Confidence, conceptual clarity, or tool readiness is fragile';
        $reasons[]   = 'Learner needs stabilization before progression';
        $rules_hit[] = 'P1_FOUNDATION_BUILDING';

        return array(
            'direction_code' => 'foundation_building',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * PRIORITY 2 — GUIDED LEARNING
     * ------------------------------ */
    if (
        in_array($preference, array('guided_first','examples_first')) &&
        in_array($application, array('dont_start','follow_only','need_help'))
    ) {

        $reasons[]   = 'Learner prefers guidance and structured progression';
        $reasons[]   = 'Application ability indicates need for scaffolding';
        $rules_hit[] = 'P2_GUIDED_LEARNING';

        return array(
            'direction_code' => 'guided_learning',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * PRIORITY 3 — CURATED LEARNING
     * ------------------------------ */
    if (
        in_array($exposure, array('heard_only','watched_read')) &&
        in_array($preference, array('examples_first','concepts_first')) &&
        $confidence !== 'low'
    ) {

        $reasons[]   = 'Learner has early exposure but needs selective reinforcement';
        $reasons[]   = 'Curated content reduces overload and builds clarity';
        $rules_hit[] = 'P3_CURATED_LEARNING';

        return array(
            'direction_code' => 'curated_learning',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * PRIORITY 4 — SKILL BOOSTER
     * ------------------------------ */
    if (
        in_array($clarity, array('clear','fragile')) &&
        in_array($application, array('need_help','follow_only')) &&
        in_array($motivation, array('career_growth','immediate_pressure'))
    ) {

        $reasons[]   = 'Concepts exist but application is weak';
        $reasons[]   = 'High motivation justifies focused reinforcement';
        $rules_hit[] = 'P4_SKILL_BOOSTER';

        return array(
            'direction_code' => 'skill_booster',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * PRIORITY 5 — ACTIVE LEARNING
     * ------------------------------ */
    if (
        $application === 'independent' &&
        in_array($friction, array('manageable','steady')) &&
        in_array($confidence, array('high','situational'))
    ) {

        $reasons[]   = 'Learner is ready to learn by doing';
        $reasons[]   = 'Low friction supports experiential progression';
        $rules_hit[] = 'P5_ACTIVE_LEARNING';

        return array(
            'direction_code' => 'active_learning',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * PRIORITY 6 — LEVEL UP
     * ------------------------------ */
    if (
        $exposure === 'hands_on' &&
        $clarity === 'clear' &&
        $application === 'independent' &&
        in_array($direction_i, array('career_growth','career_advancement'))
    ) {

        $reasons[]   = 'Strong foundation detected';
        $reasons[]   = 'Learner intent aligns with higher responsibility';
        $rules_hit[] = 'P6_LEVEL_UP';

        return array(
            'direction_code' => 'level_up',
            'reasons'        => $reasons,
            'trace'          => array(
                'rules_hit' => $rules_hit,
                'intent'    => $intent,
                'skill_gap' => $skill_gap
            )
        );
    }

    /* ------------------------------
     * FALLBACK — SAFE STRUCTURE
     * ------------------------------ */
    $reasons[]   = 'Mixed signals detected across learner inputs';
    $reasons[]   = 'Structured progression recommended for safety';
    $rules_hit[] = 'PX_FALLBACK';

    return array(
        'direction_code' => 'guided_learning',
        'reasons'        => $reasons,
        'trace'          => array(
            'rules_hit' => $rules_hit,
            'intent'    => $intent,
            'skill_gap' => $skill_gap
        )
    );
}
