<?php

function generateMilestonesForDirection($direction_code) {

  switch ($direction_code) {

    case 'active_learning':
      return [
        ['code'=>'orient', 'title'=>'Orientation & Self-Discovery',
         'desc'=>'Understand scope, expectations, and self-baseline'],
        ['code'=>'practice', 'title'=>'Hands-On Practice',
         'desc'=>'Apply learning through active experimentation'],
        ['code'=>'reflect', 'title'=>'Reflection & Refinement',
         'desc'=>'Review outcomes and refine understanding']
      ];

    case 'guided_learning':
      return [
        ['code'=>'foundation', 'title'=>'Guided Foundations',
         'desc'=>'Build fundamentals with structured guidance'],
        ['code'=>'reinforcement', 'title'=>'Reinforcement',
         'desc'=>'Strengthen concepts through guided repetition'],
        ['code'=>'application', 'title'=>'Supported Application',
         'desc'=>'Apply learning with scaffolded support']
      ];

    case 'skill_booster':
      return [
        ['code'=>'focus', 'title'=>'Focused Refresh',
         'desc'=>'Target specific weak areas quickly'],
        ['code'=>'intensity', 'title'=>'Intensive Practice',
         'desc'=>'Short bursts of high-impact learning'],
        ['code'=>'validation', 'title'=>'Skill Validation',
         'desc'=>'Confirm readiness and confidence']
      ];

    case 'level_up':
      return [
        ['code'=>'stretch', 'title'=>'Stretch Learning',
         'desc'=>'Push beyond current comfort zone'],
        ['code'=>'complexity', 'title'=>'Complex Problem Solving',
         'desc'=>'Handle advanced scenarios'],
        ['code'=>'mastery', 'title'=>'Mastery Consolidation',
         'desc'=>'Stabilize high-level capability']
      ];

    default:
      return [];
  }
}
