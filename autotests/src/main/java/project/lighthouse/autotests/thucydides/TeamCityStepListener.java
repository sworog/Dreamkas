package project.lighthouse.autotests.thucydides;

import net.thucydides.core.Thucydides;
import net.thucydides.core.model.DataTable;
import net.thucydides.core.model.Story;
import net.thucydides.core.model.TestOutcome;
import net.thucydides.core.steps.ExecutedStepDescription;
import net.thucydides.core.steps.StepFailure;
import net.thucydides.core.steps.StepListener;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.HashMap;
import java.util.Map;

public class TeamCityStepListener implements StepListener {

    private static final String messageTemplate = "##teamcity[%s %s]";
    private static final String propertyTemplate = " %s='%s'";

    private static final HashMap<String,String> escapeChars = new HashMap(){
        {
            put("\'", "|\'");
            put("\n", "|n");
            put("\r", "|r");
            put("\\|", "||");
            put("\\[", "|[");
            put("\\]", "|]");
        }
    };

    private Logger logger;

    public TeamCityStepListener(Logger logger) {
        this.logger = logger;
    }

    public TeamCityStepListener() {
        this(LoggerFactory.getLogger(TeamCityStepListener.class));
    }

    private String escapeProperty(String value) {
        for (Map.Entry<String, String> escapeChar: escapeChars.entrySet()) {
            value = value.replaceAll(escapeChar.getKey(), escapeChar.getValue());
        }
        return value;
    }

    private void printMessage(String messageName, Map<String,String> properties) {
        StringBuilder propertiesBuilder = new StringBuilder();
        for (Map.Entry<String, String> property: properties.entrySet()) {
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
        Map<String,String> properties = new HashMap<>();
        properties.put("name", description);
        printMessage(messageName, properties);
    }

    private void printMessage(String messageName) {
        Map<String,String> properties = new HashMap<>();
        printMessage(messageName, properties);
    }

    @Override
    public void testSuiteStarted(Class<?> storyClass) {
        printMessage("testSuiteStarted", storyClass.getSimpleName());
    }

    @Override
    public void testSuiteStarted(Story story) {
        printMessage("testSuiteStarted", story.getName());
    }

    @Override
    public void testSuiteFinished() {
        printMessage("testSuiteFinished");
    }

    @Override
    public void testStarted(String description) {
        printMessage("testStarted", description);
    }

    @Override
    public void testFinished(TestOutcome result) {
        printMessage("testFinished", result.getTitle());
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

        printMessage("testFailed", testOutcome.getTitle());
    }

    @Override
    public void testIgnored() {
        printMessage("testIgnored");
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
