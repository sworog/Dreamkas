package project.lighthouse.autotests.jbehave.departmentManager.search;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

public class ThenSearchSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @Then("the user checks the writeOff with sku '$number' in search results")
    public void thenTheUserChecksTheWriteOffInSearch(String number) {
        writeOffSteps.writeOffSearchResultCheck(number);
    }

    @Then("the user checks the writeOff search result list contains entry $examplesTable")
    public void thenTheUserChecksTheWriteOff(ExamplesTable examplesTable) {
        writeOffSteps.compareWithExampleTable(examplesTable);
    }

    @Then("the user checks writeOff highlighted text is '$expectedText'")
    public void thenTheUserChecksWriteOffHighlightedText(String expectedText) {
        writeOffSteps.writeOffHighLightTextCheck(expectedText);
    }

    @Then("the user checks the writeOff search result list contains stored values entry")
    public void thenTheUserChecksTheInvoiceSearchResult() {
        writeOffSteps.compareWithExampleTable();
    }

}
