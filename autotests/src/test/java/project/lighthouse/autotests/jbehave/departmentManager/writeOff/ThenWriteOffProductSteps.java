package project.lighthouse.autotests.jbehave.departmentManager.writeOff;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

public class ThenWriteOffProductSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @Then("the user checks the writeOff products list contains entry $examplesTable")
    public void thenTheUserChecksTheWriteOffProductsListContainsEntry(ExamplesTable examplesTable) {
        writeOffSteps.writeOffProductCompareWithExampleTable(examplesTable);
    }
}
