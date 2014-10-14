package ru.dreamkas.demo;

import net.thucydides.core.model.DataTable;
import net.thucydides.core.model.Story;
import net.thucydides.core.model.TestOutcome;
import net.thucydides.core.steps.ExecutedStepDescription;
import net.thucydides.core.steps.StepFailure;
import net.thucydides.core.steps.StepListener;
import ru.dreamkas.storage.DefaultStorage;
import ru.dreamkas.storage.DemoModeConfigurable;

import java.util.Map;

public class DemoStepListener implements StepListener {

    private ThucydidesControlThread thucydidesControlThread;
    private DemoModeConfigurable demoModeConfiguration = DefaultStorage.getDemoModeConfigurableStorage();

    public DemoStepListener() {
        if (demoModeConfiguration.isDemoModeOn()) {
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
        if (demoModeConfiguration.isDemoModeOn()) {
            thucydidesControlThread.setScenarioName(description);
        }
    }

    @Override
    public void testFinished(TestOutcome result) {
        if (!result.isDataDriven() && demoModeConfiguration.isDemoModeOn()) {
            thucydidesControlThread.removeScenarioSteps();
        }
    }

    @Override
    public void testRetried() {
    }

    @Override
    public void stepStarted(ExecutedStepDescription description) {
        if (demoModeConfiguration.isDemoModeOn()) {
            thucydidesControlThread.setCurrentStepText(String.format("Current step: %s", description.getTitle()));
            while (DefaultStorage.getDemoModeConfigurableStorage().isDemoModePaused()) {
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
        if (demoModeConfiguration.isDemoModeOn()) {
            thucydidesControlThread.removeScenarioSteps();
        }
    }

    @Override
    public void assumptionViolated(String message) {
    }
}
