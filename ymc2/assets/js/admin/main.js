import { initTabs } from './features/tabsControl.js';
import { accordionTabs } from './utils/accordionTabs.js';
import { tooltip } from './utils/tooltip.js';
import { getTaxonomies, getTerms, clearTaxonomies, updatedTaxonomies } from './features/taxonomyManager.js';
import { setupSortTaxonomies } from './features/sortTaxonomies.js';
import { selectAllTerms } from './features/selectTerms.js';
import { setupSortTerms } from './features/sortTerms.js';
import { loadedFeedPosts, setupSearchFeedPosts } from './features/feedLoader.js';
import { addSelectedPosts, removeSelectedPosts } from './features/postSelection.js';
import { setupExpandCollapse } from './features/postExpand.js';
import { setupSortingPosts } from './features/sortPosts.js';
import { setupExcludedPosts } from './features/excludedPosts.js';
import { setupFilterBuilder } from './features/filterBuilder.js';
import { setupAppearanceControls } from './features/appearance.js';
import { setupLayout } from './features/layout.js';
import { setupTypography } from './features/typography.js';
import { setupSearchControls } from './features/search.js';
import { setupAdvancedControls } from './features/advanced.js';
import { thickBoxTaxonomy } from './features/thickBoxTaxonomy.js';
import { thickBoxTerm } from './features/thickBoxTerm.js';
import { colorPicker } from './utils/colorPicker.js';
import { toggleSwitch } from './utils/toggleSwitch.js';
import { initPreloader } from './utils/preloader.js';
import { exportSettings, importSettings } from './features/importExport.js';


(function($) {

    document.addEventListener('DOMContentLoaded', () => {
        initTabs();
        accordionTabs();
        getTaxonomies();
        getTerms();
        clearTaxonomies();
        updatedTaxonomies();
        addSelectedPosts();
        removeSelectedPosts();
        setupSortTaxonomies();
        setupSortTerms();
        setupExpandCollapse();
        selectAllTerms();
        loadedFeedPosts();
        setupSearchFeedPosts();
        setupSortingPosts();
        setupExcludedPosts();
        setupFilterBuilder();
        setupAppearanceControls($);
        setupLayout();
        setupTypography();
        setupSearchControls();
        setupAdvancedControls();
        tooltip($);
        thickBoxTaxonomy();
        thickBoxTerm();
        colorPicker($);
        toggleSwitch();
        initPreloader();
        exportSettings();
        importSettings();


    });

})(jQuery);













