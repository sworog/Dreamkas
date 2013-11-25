package project.lighthouse.autotests.steps;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.xml.sax.SAXException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.console.ConsoleCommand;
import project.lighthouse.autotests.console.ConsoleCommandResult;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.XmlReplacement;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import java.io.File;
import java.io.IOException;

public class ConsoleCommandSteps extends ScenarioSteps {

    public ConsoleCommandSteps(Pages pages) {
        super(pages);
    }


    @Step
    public void runFixtureCommand() throws IOException, InterruptedException, TransformerException, ParserConfigurationException, SAXException {
        //TODO refactor
        String directoryPath = System.getProperty("user.dir") + "/xml/fixtures/sales";
        File patternFile = new File(directoryPath + "/salesPattern.xml");
        String filePath = directoryPath + "/sales.xml";
        new XmlReplacement(patternFile).createFile(new DateTimeHelper("today-5days").convertDate(), new File(filePath));
        String consoleCommand = String.format("bundle exec cap autotests symfony:import:sales:local -S file=%s", filePath);
        runConsoleCommand(consoleCommand, "backend");
    }

    @Step
    public void runNegativeFixtureCommand(String days) throws IOException, InterruptedException, TransformerException, ParserConfigurationException, SAXException {
        //TODO refactor
        String directoryPath = System.getProperty("user.dir") + "/xml/fixtures/sales";
        File patternFile = new File(directoryPath + "/negativeSalesPattern.xml");
        String filePath = directoryPath + "/negativeSales.xml";
        new XmlReplacement(patternFile).createFile(new DateTimeHelper("today-" + days + "days").convertDate(), new File(filePath));
        String consoleCommand = String.format("bundle exec cap autotests symfony:import:sales:local -S file=%s", filePath);
        runConsoleCommand(consoleCommand, "backend");
    }

    @Step
    public void runCapAutoTestsSymfonyProductsRecalculateMetrics() throws IOException, InterruptedException {
        String consoleCommand = "bundle exec cap autotests symfony:products:recalculate_metrics";
        runConsoleCommand(consoleCommand, "backend");
    }

    @Step
    public void runConsoleCommand(String command, String folder) throws IOException, InterruptedException {
        String host = StaticData.WEB_DRIVER_BASE_URL.replaceAll("http://(.*).autotests.webfront.lighthouse.cs", "$1");
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, host).exec(command);
        if (!consoleCommandResult.isOk()) {
            String errorMessage = String.format("Output: '%s'. Command: '%s'. Host: '%s'.", consoleCommandResult.getOutput(), command, host);
            Assert.fail(errorMessage);
        }
    }
}
