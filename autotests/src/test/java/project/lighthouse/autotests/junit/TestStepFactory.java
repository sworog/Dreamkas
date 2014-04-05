package project.lighthouse.autotests.junit;

import net.thucydides.core.model.TestResult;
import net.thucydides.core.model.TestStep;

import static net.thucydides.core.model.TestResult.*;

public class TestStepFactory {

    public static TestStep getSuccessfulTestStep(String description) {
        return createNewTestStep(description, SUCCESS);
    }

    public static TestStep getSuccessfulNestedTest(String description) {
        return createNewNestedTestSteps(description, SUCCESS);
    }

    public static TestStep getFailureTestStep(String description, Throwable assertionError) {
        return createNewTestStep(description, FAILURE, assertionError);
    }

    public static TestStep getErrorTestStep(String description, Throwable assertionError) {
        return createNewTestStep(description, ERROR, assertionError);
    }

    public static TestStep getSkippedTestStep(String description) {
        return createNewTestStep(description, SKIPPED);
    }

    public static TestStep getIgnoredTestStep(String description) {
        return createNewTestStep(description, IGNORED);
    }

    public static TestStep getPendingTestStep(String description) {
        return createNewTestStep(description, PENDING);
    }

    public static TestStep createNewTestStep(String description, TestResult result, Throwable assertionError) {
        TestStep step = new TestStep(description);
        step.failedWith(assertionError);
        return step;
    }

    public static TestStep createNewTestStep(String description, TestResult result) {
        TestStep step = new TestStep(description);
//        step.addScreenshot(new ScreenshotAndHtmlSource(new File(description + ".png"), new File(description + ".html")));
        step.setResult(result);
        step.setDuration(100);
        return step;
    }

    public static TestStep createNewNestedTestSteps(String description, TestResult result) {
        TestStep step = new TestStep(description);
        TestStep child1 = new TestStep(description);
        TestStep child2 = new TestStep(description);

        //child1.addScreenshot(new ScreenshotAndHtmlSource(new File(description + ".png"), new File(description + ".html")));
        child1.setResult(result);
        child1.setDuration(100);

        //child2.addScreenshot(new ScreenshotAndHtmlSource(new File(description + ".png"), new File(description + ".html")));
        child2.setResult(result);
        child2.setDuration(100);

        step.addChildStep(child1);
        step.addChildStep(child2);

        return step;
    }
}
