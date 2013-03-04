package project.lighthouse.autotests.jbehave;

import org.jbehave.core.annotations.*;

import net.thucydides.core.annotations.Steps;
import project.lighthouse.autotests.steps.ProductSteps;

public class ProductUserSteps {
	
	@Steps
	ProductSteps productSteps;
	
	@Given("the user is on the order create page")
    public void GivenTheUserIsOnTheOrderCreatePage() {
        productSteps.IsTheProductCreatePage();
    }
	
	@Given("the user is on the order edit page")
	public void GivenTheUserIsOnTheOrderEditPage(){
		productSteps.IsTheProductEditPage();
	}
	@Given("the user is on the order card view")
	public void GivenTheUserIsOnTheOrderCardView(){
		productSteps.IsTheProductCardViewPage();
	}

    @Given("the user is on the product list page")
    public void GivenTheUserIsOnTheProductListPage(){
        productSteps.IsTheProductListPageOpen();
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
    	productSteps.CreateButtonClick();
    }
    
    @When("the user clicks the cancel button")
    public void WhenTheUserClickCancelEditButton(){
    	productSteps.CancelButtonClick();
    }

    @When("the user creates new product from product list page")
    public void WhenTheUSerCreatesNewProduct(){
        productSteps.CreateNewProductButtonClick();
    }
    
    @Then("the user checks '$elementName' value is '$expectedValue'")
    public void ThenTheUserChecksValue(String elementName, String expectedValue){
    	productSteps.CheckValue(elementName, expectedValue);
    }
    
    @Then("the user checks is all good")
    public void ThenTheUserChecksIsAllGood(){
    	//checks
    }

    @Then("the user checks the product with '$skuValue' sku has 'name' equal to '$expectedValue'")
    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        productSteps.CheckProductWithSkuHasExpectedValue(skuValue, expectedValue);
    }
    
    /*@Then("they should see the definition '$definition'")
    public void thenTheyShouldSeeADefinitionContainingTheWords(String definition) {
    }*/	

}
