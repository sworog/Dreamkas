package project.lighthouse.autotests.jbehave;

import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;

import net.thucydides.core.annotations.Steps;
import project.lighthouse.autotests.steps.ProductSteps;

public class ProfuctOrderSteps {
	
	@Steps
	ProductSteps productSteps;
	
	@Given("the user is on the order create page")
    public void GivenTheUserIsOnTheOrderCreatePage() {
        productSteps.IstheOrderCreatePage();
    }
	
	@Given("the user is on the order edit page")
	public void GivenTheUserIsOnTheOrderEditPage(){
		productSteps.IsTheOrderEditPage();
	}
	@Given("the user is on the order card view")
	public void GivenTheUserIsOnTheOrderCardView(){
		productSteps.IsTheOrderCardViewPage();
	}

    @When("the user inputs '$inputText' in '$elementName' field")
    public void WhenTheUserInputsTextInTheField(String inputText, String elementName) {
        productSteps.FieldInput(elementName, inputText);
    }
    
    @When("the user selects '$value' in '$elementName' dropdown")
    public void WhenTheUserSelectsValueInDropDown(String value, String elementName){
    	productSteps.SelectDropdown(elementName, value);
    }
    
    @When("the user edits '$inputText' in '$elementName' field")
    public void WhenTheUserEditsTextInTheField(String inputText, String elementName) {
        productSteps.FieldEdit(elementName, inputText);
    }
    
    @When("the user clicks the create button")
    public void WhenTheUserClicksOnCreateButton(){
    	productSteps.CreateButtonClick();
    }
    
    @When("the user clicks the edit button")
    public void WhenTheUserClicksOnEditButton(){
    	productSteps.EditButtonClick();
    }
    
    @When("the user clicks the cancel button")
    public void WhenTheUserClickCancelEditButton(){
    	productSteps.CancelButtonClick();
    }
    
    @Then("the user checks '$elementName' value is '$expectedValue'")
    public void ThenTheUserChecksValue(String elementName, String expectedValue){
    	productSteps.CheckValue(elementName, expectedValue);
    }
    
    @Then("the user checks is all good")
    public void ThenTheUserChecksIsAllGood(){
    	//checks
    }    
    
    /*@Then("they should see the definition '$definition'")
    public void thenTheyShouldSeeADefinitionContainingTheWords(String definition) {
    }*/	

}
