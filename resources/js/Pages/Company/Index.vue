<style lang="scss" scoped>
.company-logo {
  top: -40px;
  width: 225px;
}
.company-name {
  margin-bottom: 17px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <!-- company cover -->
    <div class="cover mb3" :style="'height: 25vh; background: url(https://images.unsplash.com/photo-1531973576160-7125cd663d86?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1500&q=80) no-repeat center center'"></div>

    <!-- company name + data -->
    <div class="ph2 ph5-ns mb4">
      <div class="flex relative">
        <div class="company-logo relative ba bb-gray pa2 br3 bg-white mr3-ns">
          <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fih1.redbubble.net%2Fimage.445715442.6589%2Fpp%2C550x550.u3.jpg&f=1&nofb=1" />
        </div>
        <div class="mt3">
          <h2 class="mt0 fw4 f3 mb4 company-name">
            {{ $page.props.auth.company.name }} <a href="https://dundermifflin.com" class="ml3 f6 fw4 no-underline gray bb-0">https://dundermifflin.com</a>
          </h2>
          <div class="flex">
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">number of employees</p>
              <p class="mt0">{{ statistics.number_of_employees }}</p>
            </div>
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">number of teams</p>
              <p class="mt0">{{ statistics.number_of_teams }}</p>
            </div>
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">founded</p>
              <p class="mt0">1987</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <div class="cf mw9 center">
        <div class="fl w-third-l w-100">
          <company-news :news="latestNews" />

          <questions :questions="latestQuestions" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <recent-ships :ships="latestShips" />
          <recent-skills :skills="latestSkills" />
        </div>

        <div class="fl w-third-l w-100 pl4-l">
          <guess-employee-game v-if="game" :game="game" />

          <birthdays :birthdays="birthdaysThisWeek" />

          <new-hires :hires="newHiresThisWeek" />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Questions from '@/Pages/Company/Partials/Questions';
import GuessEmployeeGame from '@/Pages/Company/Partials/GuessEmployeeGame';
import Birthdays from '@/Pages/Company/Partials/Birthdays';
import CompanyNews from '@/Pages/Company/Partials/CompanyNews';
import NewHires from '@/Pages/Company/Partials/NewHires';
import RecentShips from '@/Pages/Company/Partials/RecentShips';
import RecentSkills from '@/Pages/Company/Partials/RecentSkills';

export default {
  components: {
    Layout,
    Questions,
    GuessEmployeeGame,
    Birthdays,
    CompanyNews,
    NewHires,
    RecentShips,
    RecentSkills,
  },

  props: {
    statistics: {
      type: Object,
      default: null,
    },
    latestQuestions: {
      type: Object,
      default: null,
    },
    birthdaysThisWeek: {
      type: Array,
      default: null,
    },
    newHiresThisWeek: {
      type: Array,
      default: null,
    },
    latestShips: {
      type: Array,
      default: null,
    },
    latestSkills: {
      type: Object,
      default: null,
    },
    latestNews: {
      type: Object,
      default: null,
    },
    game: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};
</script>
