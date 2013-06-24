package project.lighthouse.autotests.pages.commercialManager.catalog;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.elements.Input;

public class ClassPage extends CatalogPage {

    @FindBy(xpath = "//*[@class='button button_color_blue catalog__addGroupLink editor__control']")
    WebElementFacade addNewGroupButton;

    public ClassPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addNewButtonClick() {
        addNewGroupButton.click();
    }

    @Override
    public void addNewButtonConfirmClick() {
        findBy("//*[@class='form catalog__addGroupForm']//*[@class='button button_color_blue']/input").click();
        preloaderWait();
    }

    @Override
    public void createElements() {
        items.put("name", new Input(this, By.xpath("//form[@class='form catalog__addGroupForm']//input[@name]")));
    }

    @Override
    public String getItemXpath(String name) {
        String groupXpath = "//*[@class='catalog__groupLink' and text()='%s']";
        return String.format(groupXpath, name);
    }

    @Override
    public CommonItem getItem() {
        return items.get("name");
    }
}
