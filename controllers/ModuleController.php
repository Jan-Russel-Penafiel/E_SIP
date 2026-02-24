<?php
/**
 * Module Controller
 * Handles module browsing, filtering, and detail views.
 */

namespace Controllers;

use Models\Module;
use Models\Challenge;

class ModuleController extends BaseController
{
    private Module $moduleModel;
    private Challenge $challengeModel;

    public function __construct()
    {
        parent::__construct();
        $this->moduleModel    = new Module();
        $this->challengeModel = new Challenge();
    }

    /** List all modules with optional filters */
    public function index(): void
    {
        $this->auth->requireAuth();

        $language   = $this->query('language');
        $difficulty = $this->query('difficulty');

        $modules    = $this->moduleModel->getFiltered(
            $language ?: null,
            $difficulty ?: null
        );
        $languages  = $this->moduleModel->getLanguages();
        $user       = $this->auth->user();
        $challenges = $this->challengeModel->all();

        // Calculate progress per module
        $moduleProgress = [];
        foreach ($modules as $module) {
            $moduleProgress[$module['id']] = $this->moduleModel->calculateProgress(
                $module,
                $user['completed_challenges'] ?? [],
                $challenges
            );
        }

        $this->render('modules/index', [
            'modules'        => $modules,
            'languages'      => $languages,
            'moduleProgress' => $moduleProgress,
            'filterLang'     => $language,
            'filterDiff'     => $difficulty,
            'langConfig'     => $this->config['languages'],
        ], 'Modules - E-SIP');
    }

    /** Show a single module with its lessons and challenges */
    public function show(string $id): void
    {
        $this->auth->requireAuth();

        $module = $this->moduleModel->find($id);
        if (!$module) {
            $this->redirectWithMessage('/modules', 'Module not found.', 'error');
            return;
        }

        $challenges = $this->challengeModel->findByModule($id);
        $user       = $this->auth->user();

        $progress = $this->moduleModel->calculateProgress(
            $module,
            $user['completed_challenges'] ?? [],
            $challenges
        );

        $this->render('modules/show', [
            'module'     => $module,
            'challenges' => $challenges,
            'user'       => $user,
            'progress'   => $progress,
            'langConfig' => $this->config['languages'],
        ], $module['title'] . ' - E-SIP');
    }
}
