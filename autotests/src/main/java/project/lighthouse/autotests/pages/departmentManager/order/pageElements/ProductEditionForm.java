package project.lighthouse.autotests.pages.departmentManager.order.pageElements;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

/**
 * Page element representing product edition form
 */
public class ProductEditionForm extends ProductAdditionForm {

    public ProductEditionForm(WebDriver driver) {
        super(driver);
    }

    public void editButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
        new PreLoader(getDriver()).await();
    }

    public void cancelLinkClick() {
        new LinkFacade(this, By.className("form_orderProduct__cancelLink")).click();
    }

    public void deletionIconClick() {
        findVisibleElement(By.xpath("//*[@class='icon-remove-sign form_orderProduct__removeLink']")).click();
    }
}
