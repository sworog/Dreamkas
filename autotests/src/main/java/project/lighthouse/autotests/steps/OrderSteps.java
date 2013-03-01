package project.lighthouse.autotests.steps;

import project.lighthouse.autotests.pages.OrderCardView;
import project.lighthouse.autotests.pages.OrderCreatePage;
import project.lighthouse.autotests.pages.OrderEditPage;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;

public class OrderSteps extends ScenarioSteps{
	
	OrderCreatePage orderCreatePage;
	OrderEditPage orderEditPage;
	OrderCardView orderCardView;

	public OrderSteps(Pages pages) {
		super(pages);
	}
	
	@Step
	public void IstheOrderCreatePage(){
		orderCreatePage.open();
	}
	
	@Step
	public void IsTheOrderEditPage(){
		orderEditPage.open();
	}
	
	@Step
	public void IsTheOrderCardViewPage(){
		orderCardView.open();
	}
	
	@Step
	public void FieldInput(String elementName, String inputText){
		orderCreatePage.input(elementName, inputText);
	}
	
	@Step
	public void SelectDropdown(String elementName, String value){
		orderCreatePage.Select(elementName, value);
	}
	
	@Step
	public void CreateButtonClick(){
		orderCreatePage.CreateButtonClick();
	}
	
	@Step
	public void FieldEdit(String elementName, String inputText){
		orderEditPage.FieldEdit(elementName, inputText);
	}
	
	@Step
	public void EditButtonClick(){
		orderEditPage.EditbuttonClick();
	}
	
	@Step
	public void CancelButtonClick(){
		orderEditPage.CancelButtonClick();
	}
	
	@Step
	public void CheckValue(String elementName, String expectedValue){
		orderCardView.CheckValue(elementName, expectedValue);
	}
}
