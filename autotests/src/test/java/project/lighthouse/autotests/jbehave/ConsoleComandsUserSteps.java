package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.xml.sax.SAXException;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.XmlReplacement;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import java.io.File;
import java.io.IOException;

public class ConsoleComandsUserSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user runs the prepare fixture data cap command for inventory testing")
    public void givenTheRobotRunsThePrepareFixtureDataCommand() throws IOException, InterruptedException, TransformerException, ParserConfigurationException, SAXException {
        String directoryPath = System.getProperty("user.dir") + "/xml/fixtures/sales";
        File patternFile = new File(directoryPath + "/salesPattern.xml");
        String filePath = directoryPath + "/sales.xml";
        new XmlReplacement(patternFile).createFile(new DateTimeHelper("today-5days").convertDate(), new File(filePath));
        String consoleCommand = String.format("cap autotests symfony:import:sales:local -S file=%s", filePath);
        consoleCommandSteps.runConsoleCommand(consoleCommand, "backend");
    }

    @Given("the user runs the recalculate_metrics cap comand")
    public void givenTheRobotRunsTheRecalculateMetricsCapComand() throws IOException, InterruptedException {
        String consoleCommand = "cap autotests symfony:products:recalculate_metrics";
        consoleCommandSteps.runConsoleCommand(consoleCommand, "backend");
    }
}
