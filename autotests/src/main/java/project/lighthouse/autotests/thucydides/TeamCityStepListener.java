package project.lighthouse.autotests.thucydides;

import net.thucydides.core.model.DataTable;
import net.thucydides.core.model.Story;
import net.thucydides.core.model.TestOutcome;
import net.thucydides.core.steps.ExecutedStepDescription;
import net.thucydides.core.steps.StepFailure;
import net.thucydides.core.steps.StepListener;

import java.util.HashMap;
import java.util.Map;

public class TeamCityStepListener implements StepListener {

    private static final String messageTemplate = "##teamcity[%s %s]";
    private static final String propertyTemplate = " %s='%s'";

    private String formatMessage(String messageName, Map<String,String> properties) {
        StringBuilder propertiesBuilder = new StringBuilder();
        for (Map.Entry<String, String> property: properties.entrySet()) {
            propertiesBuilder.append(String.format(propertyTemplate, property.getKey(), property.getValue()));
        }
        return String.format(messageTemplate, messageName, propertiesBuilder.toString());
    }

    private String formatMessage(String messageName, String description) {
        Map<String,String> properties = new HashMap<>();
        properties.put("name", description);
        return formatMessage(messageName, properties);
    }

    private void printMessage(String message) {
        System.out.println(message);
    }

    @Override
    public void testSuiteStarted(Class<?> storyClass) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void testSuiteStarted(Story story) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void testSuiteFinished() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void testStarted(String description) {
        String message = formatMessage("testStarted", description);
        printMessage(message);
    }

    @Override
    public void testFinished(TestOutcome result) {
        String message = formatMessage("testFinished", result.getTitle());
        printMessage(message);
    }

    @Override
    public void stepStarted(ExecutedStepDescription description) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void skippedStepStarted(ExecutedStepDescription description) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void stepFailed(StepFailure failure) {
        //To change body of implemented methods use File | Settings | File Templates.
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
    public void testFailed(TestOutcome testOutcome, Throwable cause) {

        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void testIgnored() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void notifyScreenChange() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void useExamplesFrom(DataTable table) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void exampleStarted(Map<String, String> data) {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    @Override
    public void exampleFinished() {
        //To change body of implemented methods use File | Settings | File Templates.
    }
}
