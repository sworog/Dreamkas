package project.lighthouse.autotests.thucydides;

import net.thucydides.core.model.DataTable;
import net.thucydides.core.model.Story;
import net.thucydides.core.model.TestOutcome;
import net.thucydides.core.model.TestStep;
import net.thucydides.core.steps.ExecutedStepDescription;
import net.thucydides.core.steps.StepFailure;
import net.thucydides.core.steps.StepListener;
import org.apache.commons.lang.exception.ExceptionUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.*;

public class TeamCityStepListener implements StepListener {

    private static final String messageTemplate = "##teamcity[%s %s]";
    private static final String propertyTemplate = " %s='%s'";

    private static final HashMap<String, String> escapeChars = new LinkedHashMap() {
        {
            put("\\|", "||");
            put("\'", "|\'");
            put("\n", "|n");
            put("\r", "|r");
            put("\\[", "|[");
            put("\\]", "|]");
        }
    };

    private Logger logger;

    private Stack<String> suiteStack = new Stack<String>();

    private String description;
    private Integer examplesTestCount = 0;
    private HashMap<Integer, String> exampleTestNames = new HashMap<>();

    public TeamCityStepListener(Logger logger) {
        this.logger = logger;
    }

    public TeamCityStepListener() {
        this(LoggerFactory.getLogger(TeamCityStepListener.class));
    }

    private String escapeProperty(String value) {
        for (Map.Entry<String, String> escapeChar : escapeChars.entrySet()) {
            value = value.replaceAll(escapeChar.getKey(), escapeChar.getValue());
        }
        return value;
    }

    private void printMessage(String messageName, Map<String, String> properties) {
        StringBuilder propertiesBuilder = new StringBuilder();
        for (Map.Entry<String, String> property : properties.entrySet()) {
            propertiesBuilder.append(
                    String.format(
                            propertyTemplate,
                            property.getKey(),
                            escapeProperty(property.getValue())
                    )
            );
        }
        String message = String.format(messageTemplate, messageName, propertiesBuilder.toString());
        logger.info(message);
    }

    private void printMessage(String messageName, String description) {
        Map<String, String> properties = new HashMap<>();
        properties.put("name", description);
        printMessage(messageName, properties);
    }

    private void printMessage(String messageName) {
        Map<String, String> properties = new HashMap<>();
        printMessage(messageName, properties);
    }

    @Override
    public void testSuiteStarted(Class<?> storyClass) {
        printMessage("testSuiteStarted", storyClass.getSimpleName());
    }

    @Override
    public void testSuiteStarted(Story story) {
        String storyName = story.getName();
        suiteStack.push(storyName);
        printTestSuiteStarted(storyName);
    }

    @Override
    public void testSuiteFinished() {
        String suiteName = suiteStack.pop();
        printTestSuiteFinished(suiteName);
    }

    @Override
    public void testStarted(String description) {
        printTestStarted(description);
        this.description = description;
    }

    @Override
    public void testFinished(TestOutcome result) {
        if (result.isDataDriven() && (result.isFailure() || result.isError())) {
            printExampleResults(result);
        } else if (!result.isDataDriven() && (result.isFailure() || result.isError())) {
            printFailure(result);
            printTestFinished(result);
        } else if (result.isSkipped() || result.isPending()) {
            printTestIgnored(result);
            printTestFinished(result);
        }
    }

    private void printFailure(TestOutcome result) {
        HashMap<String, String> properties = new HashMap<String, String>();
        properties.put("name", result.getTitle());
        properties.put("message", result.getTestFailureCause().getMessage());
        properties.put("details", getStepsInfo(result.getTestSteps()));
        printMessage("testFailed", properties);
    }

    private void printExampleResults(TestOutcome result) {
        List<TestStep> testSteps = result.getTestSteps();
        for (int i = 0; i < testSteps.size(); i++) {
            if (!testSteps.get(i).getChildren().isEmpty()) {
                List<TestStep> childrenTestSteps = result.getTestSteps().get(i).getChildren();
                String testName = exampleTestNames.get(i - 1);
                printMessage("testStarted", testName);
                if (hasFailureStep(childrenTestSteps)) {
                    String getStepsInfo = getStepsInfo(childrenTestSteps);
                    HashMap<String, String> properties = new HashMap<String, String>();
                    properties.put("name", testName);
                    properties.put("details", getStepsInfo);
                    printMessage("testFailed", properties);
                }
                printMessage("testFinished", testName);
            }
        }
        examplesTestCount = 0;
        exampleTestNames.clear();
    }

    private String getStepsInfo(List<TestStep> testSteps) {
        StringBuilder builder = new StringBuilder("Steps:\r\n");
        for (int i = 0; i < testSteps.size(); i++) {
            String stepMessage = String.format("%s (%s) -> %s\r\n", testSteps.get(i).getDescription(), testSteps.get(i).getDurationInSeconds(), getResultMessage(testSteps.get(i)));
            builder.append(stepMessage);
        }
        return builder.append("\r\n").toString();
    }

    private Boolean hasFailureStep(List<TestStep> testSteps) {
        for (int i = 0; i < testSteps.size(); i++) {
            if (testSteps.get(i).isError() || testSteps.get(i).isFailure()) {
                return true;
            }
        }
        return false;
    }

    private String getResultMessage(TestStep testStep) {
        StringBuilder builder = new StringBuilder();
        builder.append(testStep.getResult().toString());
        if (testStep.isFailure() || testStep.isError()) {
            builder.append(
                    String.format("\r\n\n%s", ExceptionUtils.getStackTrace(testStep.getException().getCause()))
            );
        }
        return builder.toString();
    }

    private void printTestStarted(String name) {
        printMessage("testStarted", name);
    }

    private void printTestIgnored(TestOutcome result) {
        printMessage("testIgnored", result.getTitle());
    }

    private void printTestFinished(TestOutcome result) {
        printMessage("testFinished", result.getTitle());
    }

    private void printTestSuiteFinished(String name) {
        printMessage("testSuiteFinished", name);
    }

    private void printTestSuiteStarted(String name) {
        printMessage("testSuiteStarted", name);
    }

    @Override
    public void testFailed(TestOutcome testOutcome, Throwable cause) {
        printMessage("testFailed", testOutcome.getTitle());
    }

    @Override
    public void testIgnored() {
        printMessage("testIgnored");
    }

    @Override
    public void stepStarted(ExecutedStepDescription description) {
    }

    @Override
    public void skippedStepStarted(ExecutedStepDescription description) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepFailed(StepFailure failure) {
    }

    @Override
    public void lastStepFailed(StepFailure failure) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepIgnored() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepIgnored(String message) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepPending() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepPending(String message) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepFinished() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void notifyScreenChange() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void useExamplesFrom(DataTable table) {
        //TODO run examples test in test suite
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void exampleStarted(Map<String, String> data) {
        String testName = String.format("%s - %s", description, data.toString());
        exampleTestNames.put(examplesTestCount, testName);
        examplesTestCount++;
    }

    @Override
    public void exampleFinished() {
        //To change body of implemented methods use File | Settings | File Templates.
    }
}
