package project.lighthouse.autotests.demo;

import net.thucydides.core.model.DataTable;
import net.thucydides.core.model.Story;
import net.thucydides.core.model.TestOutcome;
import net.thucydides.core.steps.ExecutedStepDescription;
import net.thucydides.core.steps.StepFailure;
import net.thucydides.core.steps.StepListener;
import project.lighthouse.autotests.StaticData;

import java.util.Map;

public class DemoStepListener implements StepListener {

    ThucydidesControlThread thucydidesControlThread;

    public DemoStepListener() {
        if (StaticData.demoMode) {
            keyListenerThreadStart();
        }
    }

    public void keyListenerThreadStart() {
        thucydidesControlThread = new ThucydidesControlThread();
        thucydidesControlThread.start();
    }

    @Override
    public void testSuiteStarted(Class<?> storyClass) {
    }

    @Override
    public void testSuiteStarted(Story story) {
    }

    @Override
    public void testSuiteFinished() {
    }

    @Override
    public void testStarted(String description) {
        if (StaticData.demoMode) {
            thucydidesControlThread.setScenarioName(description);
        }
    }

    @Override
    public void testFinished(TestOutcome result) {
        if (!result.isDataDriven() && StaticData.demoMode) {
            thucydidesControlThread.removeScenarioSteps();
        }
    }

    @Override
    public void testRetried() {
    }

    @Override
    public void stepStarted(ExecutedStepDescription description) {
        if (StaticData.demoMode) {
            thucydidesControlThread.setCurrentStepText(String.format("Current step: %s", description.getTitle()));
            while (StaticData.isPaused) {
                try {
                    Thread.sleep(100);
                } catch (InterruptedException ignored) {
                }
            }
            thucydidesControlThread.addStep(description.getTitle());
            try {
                Thread.sleep(500);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }

    @Override
    public void skippedStepStarted(ExecutedStepDescription description) {
    }

    @Override
    public void stepFailed(StepFailure failure) {
    }

    @Override
    public void lastStepFailed(StepFailure failure) {
    }

    @Override
    public void stepIgnored() {
    }

    @Override
    public void stepPending() {
    }

    @Override
    public void stepPending(String message) {
    }

    @Override
    public void stepFinished() {
    }

    @Override
    public void testFailed(TestOutcome testOutcome, Throwable cause) {
    }

    @Override
    public void testIgnored() {
    }

    @Override
    public void notifyScreenChange() {
    }

    @Override
    public void useExamplesFrom(DataTable table) {
    }

    @Override
    public void exampleStarted(Map<String, String> data) {
    }

    @Override
    public void exampleFinished() {
        if (StaticData.demoMode) {
            thucydidesControlThread.removeScenarioSteps();
        }
    }

    @Override
    public void assumptionViolated(String message) {
    }
}
