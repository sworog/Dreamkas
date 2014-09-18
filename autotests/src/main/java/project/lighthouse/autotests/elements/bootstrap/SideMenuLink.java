package project.lighthouse.autotests.elements.bootstrap;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;

public class SideMenuLink extends AbstractFacade {

    public SideMenuLink(CommonPageObject pageObject, String facadeText) {
        super(pageObject, facadeText);
    }

    @Override
    public String getXpathPattern() {
        return "//*[@id='side-menu']//a[*[text()='%s']]";
    }

    public Boolean isActive() {
        return getPageObject().findVisibleElement(getFindBy()).findElement(By.xpath(".//..")).getAttribute("class").equals("active");
    }
}
